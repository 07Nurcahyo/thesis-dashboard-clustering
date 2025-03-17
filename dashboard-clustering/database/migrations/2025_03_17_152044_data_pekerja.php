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
        Schema::create('data_pekerja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kode_provinsi')->index(); //fk
            $table->year('tahun');
            $table->integer('garis_kemiskinan');
            $table->integer('upah_minimum');
            $table->integer('pengeluaran');
            $table->integer('rr_upah');
            $table->timestamps();

            $table->foreign('kode_provinsi')->references('id_provinsi')->on('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pekerja');
    }
};
