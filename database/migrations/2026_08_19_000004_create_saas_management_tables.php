<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saas_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->integer('price')->default(0);
            $table->string('billing_period', 20)->default('monthly');
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('saas_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::table('owners', function (Blueprint $table) {
            $table->string('level', 50)->default('bronze')->change();
            $table->timestamp('subscription_expires_at')->nullable()->after('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_plans');
        Schema::dropIfExists('saas_settings');

        Schema::table('owners', function (Blueprint $table) {
            $table->dropColumn('subscription_expires_at');
            // We don't revert varchar back to enum to prevent potential data loss during down migrations
        });
    }
};
