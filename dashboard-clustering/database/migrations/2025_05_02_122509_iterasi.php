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
        Schema::create('iterasi', function (Blueprint $table) {
            $table->id('id_iterasi');
            $table->float('c1');
            $table->float('c2');
            $table->float('c3');
            $table->double('distance');
            $table->double('distance_min');
            $table->double('sse');
            $table->float('c1_new');
            $table->float('c2_new');
            $table->float('c3_new');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iterasi');
    }
};
