<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();    // Contoh: app_name, app_version, company_name
            $table->text('value')->nullable();  // Nilai dari key tersebut
            $table->string('group')->default('general'); // Kelompokkan: general, appearance, contact, social
            $table->string('label');             // Label tampilan di form
            $table->string('type')->default('text'); // text, textarea, url, email, color, file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_configs');
    }
};
