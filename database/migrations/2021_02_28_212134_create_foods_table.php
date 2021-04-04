<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();

            $table->text('unsplash_id')->unique()->nullable();
            $table->string('title')->nullable();
            $table->float('price')->nullable();
            $table->integer('likes')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->string('file_path')->nullable();

            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foods');
    }
}
