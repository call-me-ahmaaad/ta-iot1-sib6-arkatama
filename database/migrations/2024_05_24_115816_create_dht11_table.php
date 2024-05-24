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
        Schema::create('dht11', function (Blueprint $table) {
            $table->id();
            $table->float('temp_c', 5, 2);
            $table->float('temp_f', 5, 2);
            $table->float('temp_k', 5, 2);
            $table->float('humid', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dht11');
    }
};
