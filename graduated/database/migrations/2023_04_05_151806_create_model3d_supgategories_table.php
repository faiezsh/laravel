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
        Schema::create('model3d_supgategories', function (Blueprint $table) {
            $table->increments('id');
			$table->softDeletes();
			$table->integer('models3DId')->unsigned();
			$table->integer('supCategoryId')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model3_dsupgategories');
    }
};
