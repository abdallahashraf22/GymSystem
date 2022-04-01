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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean("isDeleted")->default(false);
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->boolean("isDeleted")->default(false);
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->boolean("isDeleted")->default(false);
        });

        Schema::table('coaches', function (Blueprint $table) {
            $table->boolean("isDeleted")->default(false);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->boolean("isDeleted")->default(false);
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->boolean("isDeleted")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
