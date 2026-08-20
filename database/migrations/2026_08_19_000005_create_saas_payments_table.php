<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('saas_payments');

        Schema::create('saas_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_id')->nullable();
            $table->string('plan_slug', 50);
            $table->string('order_id', 100)->unique();
            $table->integer('amount');
            $table->string('status', 20)->default('unpaid'); // unpaid, paid, failed, cancelled
            $table->string('payment_method', 50)->nullable()->default('QRIS');
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_payments');
    }
};
