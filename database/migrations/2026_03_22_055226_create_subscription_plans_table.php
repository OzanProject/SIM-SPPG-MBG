<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Basic, Premium
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0); // Harga paket (bisa 0 untuk free)
            $table->integer('duration_in_days')->default(30); // Berapa lama masa aktifnya
            $table->integer('max_members')->default(0); // Batas anggota koperasi
            $table->integer('max_admins')->default(0); // Batas admin/pengurus cabang
            $table->boolean('is_active')->default(true); // Status tampil atau sembunyi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
