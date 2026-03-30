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
        Schema::table('subscription_plans', function (Blueprint $table) {
            // Limits
            $table->integer('max_users')->default(1);
            $table->integer('max_transactions_per_month')->default(0);
            $table->integer('max_items')->default(0); // Products/Inventory items
            
            // Feature Flags
            $table->boolean('has_sales')->default(false);
            $table->boolean('has_inventory')->default(false);
            $table->boolean('has_accounting_full')->default(false);
            $table->boolean('has_budgeting')->default(false);
            $table->boolean('has_procurement')->default(false);
            $table->boolean('has_hr')->default(false);
            $table->boolean('has_notifications')->default(false);
            $table->boolean('can_export')->default(false);
            
            // Labels & Psychology
            $table->string('badge_label')->nullable(); // e.g., "BEST VALUE", "POPULER"
            $table->boolean('is_highlighted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn([
                'max_users', 'max_transactions_per_month', 'max_items',
                'has_sales', 'has_inventory', 'has_accounting_full',
                'has_budgeting', 'has_procurement', 'has_hr',
                'has_notifications', 'can_export',
                'badge_label', 'is_highlighted'
            ]);
        });
    }
};
