<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_features', function (Blueprint $table) {
            $table->id();
            $table->string('icon');                          // Material Symbol name atau emoji
            $table->string('icon_type')->default('symbol'); // 'symbol' | 'emoji'
            $table->string('color_class')->default('indigo'); // indigo | purple | blue | emerald | amber | rose
            $table->string('title');
            $table->text('description');
            $table->string('size')->default('small');       // 'large' | 'medium' | 'small'
            $table->string('badge_text')->nullable();       // Optional badge e.g. "Ready to Audit"
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('order_priority')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_features');
    }
};
