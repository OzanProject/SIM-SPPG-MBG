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
        Schema::table('global_announcements', function (Blueprint $table) {
            $table->boolean('is_persistent')->default(false)->after('show_modal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_announcements', function (Blueprint $table) {
            $table->dropColumn('is_persistent');
        });
    }
};
