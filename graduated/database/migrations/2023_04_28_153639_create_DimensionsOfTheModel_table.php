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
        Schema::create('DimensionsOfTheModel', function (Blueprint $table) {
            $table->id();
            $table->integer('modelId');
            $table->integer('roomModelId');
            $table->double ('x');
            $table->double ('y');
            $table->double ('z');
            $table->double ('scale');
            $table->double ('rotate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_dimations');
    }
};
