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
        Schema::create('room_model3d', function (Blueprint $table) {
            $table->increments('id');
			$table->softDeletes();
			$table->float('rate')->default('5.0');
            $table->string('roomModelName');
            $table->string('color');
			$table->integer('companyId')->unsigned();
			$table->integer('userId')->unsigned();
            $table->double('price');
            $table->double('highe');
            $table->double('whidth');
            $table->double('Length');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_model3_d_s');
    }
};
