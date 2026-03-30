<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();              // INV-2026-0001
            $table->string('tenant_id');                            // FK ke tenants (string karena stancl)
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans');
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete();
            $table->decimal('base_amount', 15, 2);                  // Harga asli paket
            $table->decimal('discount_amount', 15, 2)->default(0); // Jumlah diskon
            $table->decimal('final_amount', 15, 2);                 // Yang harus dibayar
            $table->enum('status', ['pending', 'paid', 'expired', 'cancelled'])->default('pending');
            $table->date('due_date');                               // Batas bayar
            $table->timestamp('paid_at')->nullable();               // Kapan dibayar
            $table->string('payment_method')->nullable();           // Transfer, QRIS, dll
            $table->string('payment_proof')->nullable();            // Path bukti pembayaran
            $table->text('notes')->nullable();                      // Catatan admin
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
