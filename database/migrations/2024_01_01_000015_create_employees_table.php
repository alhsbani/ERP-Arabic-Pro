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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('company_id')->constrained();
            $table->foreignId('department_id')->nullable()->constrained();
            $table->foreignId('position_id')->nullable()->constrained();
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('national_id')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->string('city');
            $table->string('country');
            $table->date('hire_date');
            $table->enum('employment_type', ['full-time', 'part-time', 'contract'])->default('full-time');
            $table->decimal('salary', 12, 2);
            $table->string('salary_currency')->default('SAR');
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
