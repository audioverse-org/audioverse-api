<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsRecordings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagsRecordings', function (Blueprint $table) {
            $table->mediumInteger('recordingId')->unsigned();
            $table->integer('tagCategoryId')->unsigned();
            $table->bigInteger('tagId')->unsigned();
            $table->primary(['recordingId','tagCategoryId','tagId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tagsRecordings');
    }
}
