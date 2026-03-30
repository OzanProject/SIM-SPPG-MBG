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
        Schema::create('tenant_circle_menus', function (Blueprint $table) {
            $table->id();
            $table->date('target_date');
            $table->string('location_name');
            $table->integer('total_portions');
            $table->text('menu_items'); // Detailed list of food
            $table->string('documentation_photo')->nullable();
            $table->enum('status', ['draft', 'processing', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_circle_menus');
    }
};
