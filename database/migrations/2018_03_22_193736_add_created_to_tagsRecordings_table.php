<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedToTagsRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagsRecordings', function (Blueprint $table) {

            // add created column
            $table->timestamp('created')
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->after('tagId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagsRecordings', function (Blueprint $table) {

            // drop created
            $table->dropColumn('created');
        });
    }
}
