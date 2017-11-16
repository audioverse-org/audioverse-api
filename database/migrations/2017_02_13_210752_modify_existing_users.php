<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyExistingUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            // add name column
            $table->string('name', 50)
                ->after('password')
                ->comment('Required column for laravel user module');
            // add remember_token column
            $table->string('remember_token', 100)
                ->after('password')
                ->default('')
                ->comment('Required column for laravel user module');
            // change password length to 100 characters
            $table->string('password', 100)->change();
            // null the rest
            $table->string('username', 32)->nullable()->change();
            $table->string('realName', 128)->nullable()->change();
            $table->string('firstName', 45)->nullable()->change();
            $table->string('lastName', 45)->nullable()->change();
            $table->string('language')->default('en')->change();
            $table->string('birthdate')->default('1900/01/01')->change();
            $table->string('addressLine1', 255)->nullable()->change();
            $table->string('addressLine2', 255)->nullable()->change();
            $table->string('municipality', 255)->nullable()->change();
            $table->string('province', 255)->nullable()->change();
            $table->string('postalCode', 255)->nullable()->change();
            $table->string('country', 255)->nullable()->change();
            $table->string('root')->default(0)->change();
            $table->string('ownerOf', 255)->nullable()->change();
            $table->string('groups', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // drop name
            $table->dropColumn('name');
            // drop remember_token
            $table->dropColumn('remember_token');
        });
    }
}
