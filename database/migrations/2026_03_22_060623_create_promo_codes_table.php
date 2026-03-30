<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();                        // Kode unik: BARU2026
            $table->enum('type', ['fixed', 'percent']);              // Tipe: fixed (Rp) atau percent (%)
            $table->decimal('value', 15, 2);                         // Nilai diskon
            $table->date('starts_at');                               // Mulai berlaku
            $table->date('ends_at');                                 // Berakhir berlaku
            $table->integer('max_uses')->default(1);                 // Batas penggunaan total (kuota)
            $table->integer('used_count')->default(0);               // Sudah dipakai berapa kali
            $table->boolean('is_active')->default(true);             // Status aktif
            $table->timestamps();
        });

        // Pivot: promo_code dapat berlaku untuk beberapa paket tertentu
        Schema::create('promo_code_subscription_plan', function (Blueprint $table) {
            $table->foreignId('promo_code_id')->constrained('promo_codes')->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->onDelete('cascade');
            $table->primary(['promo_code_id', 'subscription_plan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_code_subscription_plan');
        Schema::dropIfExists('promo_codes');
    }
};
