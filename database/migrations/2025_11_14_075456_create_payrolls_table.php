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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_branche_id')->constrained('user_branches')->onDelete('cascade');
            $table->string('periode'); // e.g. 2025-12
            $table->integer('hari_kerja')->default(0);
            // data uang → tanpa change()
            $table->decimal('gaji_pokok', 15, 2)->nullable();
            $table->decimal('transportasi', 15, 2)->nullable();
            $table->decimal('makan', 15, 2)->nullable();
            $table->decimal('tunjangan', 15, 2)->nullable();
            $table->decimal('bonus_revenue', 15, 2)->nullable();
            $table->decimal('simpanan', 15, 2)->nullable();
            $table->decimal('potongan', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();

            // $table->integer('gaji_pokok')->default(0);
            // $table->integer('transportasi')->default(0);
            // $table->integer('tunjangan')->default(0);
            // $table->integer('bonus_revenue')->default(0);
            // $table->integer('simpanan')->default(0);
            // $table->integer('potongan')->default(0);
            // $table->integer('makan')->default(0);
            // $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
