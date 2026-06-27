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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->text('description')->nullable();
            $table->string('sku')->unique();
            $table->decimal('price', 12, 2);
            $table->decimal('cost', 12, 2);
            $table->integer('quantity')->default(0);
            $table->foreignId('category_id')->nullable()->constrained('product_categories');
            $table->foreignId('company_id')->constrained();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
