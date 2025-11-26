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

            // Foreign key to branches
       
            // Employee basic info
            $table->string('full_name');

            // Additional fields
            $table->string('grade')->nullable();              // golongan
            $table->string('address')->nullable();            // alamat
            $table->date('hire_date')->nullable();
            $table->integer('years_of_service')->default(0);  // lama bekerja
            $table->string('education')->nullable();          // pendidikan

            // Employee identification
            $table->string('employee_code')->unique()->nullable();
            $table->string('account_number', 30)->nullable();
            $table->string('npwp_number', 30)->nullable();
            $table->string('nik', 30)->nullable();

            // Job related
            $table->string('position')->nullable();

            // Active or inactive
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
