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
        // 1. Tabel Menus (Daftar Menu Makanan/Minuman)
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('category', ['makanan', 'minuman', 'snack', 'lainnya'])->default('makanan');
            $table->decimal('price', 15, 2);
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Tabel Sales (Header Penjualan)
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('date');
            $table->string('customer_name')->nullable();
            $table->enum('payment_method', ['cash', 'transfer', 'qris', 'lainnya'])->default('cash');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Relasi ke tabel journals untuk auto-journal (akuntansi)
            $table->unsignedBigInteger('journal_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Tabel Sale Details (Detail Item Penjualan)
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            // Menjadikan relasi ke menu ter-set null jika menu dihapus, agar history transaksi tidak hilang/error
            $table->foreignId('menu_id')->nullable()->constrained()->nullOnDelete();
            $table->string('menu_name')->comment('Simpan nama menu saat transaksi terjadi');
            $table->integer('quantity');
            $table->decimal('price', 15, 2)->comment('Harga satuan saat transaksi');
            $table->decimal('subtotal', 15, 2)->comment('Quantity * Price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('menus');
    }
};
