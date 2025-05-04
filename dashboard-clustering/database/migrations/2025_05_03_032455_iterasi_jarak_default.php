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
        Schema::create('iterasi_jarak_default', function (Blueprint $table) {
            $table->id('id_iterasi_jarak_default');
            $table->double('jarak_c1');
            $table->double('jarak_c2');
            $table->double('jarak_c3');
            $table->double('c_terkecil');
            $table->string('cluster_member', 2);
            $table->integer('jarak_minimum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
