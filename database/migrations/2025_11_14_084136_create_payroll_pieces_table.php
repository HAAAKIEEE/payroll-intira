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
        Schema::create('payroll_pieces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_branche_id')->constrained('user_branches')->onDelete('cascade');
            $table->string('periode');
            $table->string('jabatan')->nullable();
            $table->decimal('kesejahteraan', 15, 2)->nullable();
            $table->decimal('komunikasi', 15, 2)->nullable();
            $table->decimal('tunjangan', 15, 2)->nullable();
            $table->decimal('potongan', 15, 2)->nullable();
            $table->string('kategori')->nullable();
            $table->string('keterangan')->nullable();
            $table->date('tanggal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_pieces');
    }
};
