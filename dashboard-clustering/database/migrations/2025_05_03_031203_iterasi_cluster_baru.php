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
        Schema::create('iterasi_cluster_baru', function (Blueprint $table) {
            $table->id('id_iterasi_cluster_baru');
            $table->float('c1', 20);
            $table->float('c2', 20);
            $table->float('c3', 20);
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
