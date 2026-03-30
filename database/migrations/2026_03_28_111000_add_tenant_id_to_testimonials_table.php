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
        Schema::table('testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('testimonials', 'tenant_id')) {
                $table->string('tenant_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('testimonials', 'source')) {
                $table->enum('source', ['internal', 'tenant'])->default('internal')->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['tenant_id', 'source']);
        });
    }
};
