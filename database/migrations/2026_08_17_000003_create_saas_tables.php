<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Array of SQL files to import in dependency order
        $sqlFiles = [
            base_path('mikhmon/database/create_owners_tables.sql'),
            base_path('mikhmon/database/agent_system.sql'),
            base_path('mikhmon/database/billing_module.sql'),
            base_path('mikhmon/database/create_gold_tables.sql'),
        ];

        // Disable foreign key checks temporarily for import
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($sqlFiles as $file) {
            if (File::exists($file)) {
                $sql = File::get($file);
                
                // Remove comments
                $sql = preg_replace('/--.*\n/', '', $sql);
                
                // Split statements by semicolon
                $statements = array_filter(
                    array_map('trim', explode(';', $sql))
                );
                
                foreach ($statements as $statement) {
                    if (!empty($statement)) {
                        try {
                            DB::unprepared($statement);
                        } catch (\Exception $e) {
                            // Ignore if table/insert already exists, log others
                            if (strpos($e->getMessage(), 'already exists') === false && 
                                strpos($e->getMessage(), 'Duplicate entry') === false) {
                                \Log::warning("SQL Import warning: " . $e->getMessage());
                            }
                        }
                    }
                }
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Add owner_id to support tenancy isolation across modules
        $tablesToPartition = [
            'agents' => 'id',
            'billing_customers' => 'id',
            'support_tickets' => 'id',
            'inventory_items' => 'id',
            'network_nodes' => 'id'
        ];

        foreach ($tablesToPartition as $tableName => $afterColumn) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'owner_id')) {
                DB::statement("ALTER TABLE `{$tableName}` ADD COLUMN `owner_id` INT NULL AFTER `{$afterColumn}`");
                
                // Add index and foreign key if the owners table exists
                if (Schema::hasTable('owners')) {
                    try {
                        DB::statement("ALTER TABLE `{$tableName}` ADD CONSTRAINT `fk_{$tableName}_owner` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`id`) ON DELETE SET NULL");
                    } catch (\Exception $e) {
                        \Log::warning("Failed to add foreign key to {$tableName}: " . $e->getMessage());
                    }
                }
            }
        }
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'agent_prices',
            'agent_transactions',
            'agent_vouchers',
            'agent_topup_requests',
            'agent_commissions',
            'digiflazz_products',
            'billing_logs',
            'billing_payments',
            'billing_invoices',
            'billing_customers',
            'billing_profiles',
            'billing_settings',
            'inventory_items',
            'support_tickets',
            'network_nodes',
            'router_sessions',
            'owners'
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
