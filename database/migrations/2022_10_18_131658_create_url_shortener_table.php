<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlShortenerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_shortener', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('short_url');
            $table->string('original_url');
            $table->string('ip');
            $table->bigInteger('count');
            $table->tinyInteger('active')->default(1);
            $table->bigInteger('created_uid');
            $table->bigInteger('updated_uid')->nullable();
            $table->timestamps();

            $table->foreign('created_uid')->references('id')->on('users');
            $table->foreign('updated_uid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_shortener');
    }
}
