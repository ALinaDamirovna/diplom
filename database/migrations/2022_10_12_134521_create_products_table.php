<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('composition')->nullable();
            $table->string('photo')->nullable();
            $table->double('price', 8, 2)->default(0.0);
            $table->unsignedInteger('weight')->nullable();
            $table->double('calories', 8, 2)->nullable();
            $table->double('proteins', 8, 2)->nullable();
            $table->double('fats', 8, 2)->nullable();
            $table->double('carbohydrates', 8, 2)->nullable();
            $table->unsignedBigInteger('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
