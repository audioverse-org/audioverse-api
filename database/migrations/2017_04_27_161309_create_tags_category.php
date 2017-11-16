<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagsCategory', function (Blueprint $table) {
            $table->increments('id')->unique()->unsigned();
            $table->string('name')->unique();
            $table->string('lang', 2);
            $table->tinyInteger('contentType')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tagsCategory');
    }
}
