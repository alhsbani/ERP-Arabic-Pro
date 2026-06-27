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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->text('address_ar');
            $table->string('city');
            $table->string('city_ar');
            $table->string('country');
            $table->string('tax_number')->nullable();
            $table->string('commercial_register')->nullable();
            $table->string('logo')->nullable();
            $table->string('currency')->default('SAR');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
