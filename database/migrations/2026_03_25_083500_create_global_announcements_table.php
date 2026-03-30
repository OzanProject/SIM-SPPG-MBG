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
        Schema::create('global_announcements', function (Blueprint $col) {
            $col->id();
            $col->string('title');
            $col->text('body');
            $col->string('type')->default('info'); // info, success, warning, danger
            $col->string('target_plan')->nullable(); // slug paket (free, pro, etc) atau null untuk semua
            $col->boolean('is_active')->default(true);
            $col->boolean('show_modal')->default(true); // apakah tampil sebagai popup/modal
            $col->timestamp('expires_at')->nullable();
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_announcements');
    }
};
