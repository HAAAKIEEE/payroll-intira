<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_branche_id')->constrained('user_branches')->onDelete('cascade');
            $table->string('periode'); // format: YYYY-MM

            $table->integer('hari_kerja')->default(0);

            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('transportasi', 15, 2)->default(0);
            $table->decimal('makan', 15, 2)->default(0);

            $table->decimal('bonus_revenue', 15, 2)->default(0);
            $table->decimal('revenue_persentase', 15, 2)->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0);

            $table->decimal('tunjangan', 15, 2)->default(0);
            $table->decimal('simpanan', 15, 2)->default(0);
            $table->decimal('potongan', 15, 2)->default(0);

            $table->decimal('kpi_persentase', 15, 2)->default(0); // diperbaiki ejaannya
            $table->decimal('kpi', 15, 2)->default(0);
            $table->decimal('total_kpi', 15, 2)->default(0);

            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('take_home_pay', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
