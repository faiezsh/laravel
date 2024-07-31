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
        Schema::create('model3d', function (Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('path');
			$table->integer('companyId')->unsigned();
			$table->string('modelName');
            $table->string('typeFile');
            $table->double('price');
            $table->double('scale');
            $table->string('type');
            $table->double('x');
            $table->double('y');
            $table->double('z');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model3d');
    }
};
