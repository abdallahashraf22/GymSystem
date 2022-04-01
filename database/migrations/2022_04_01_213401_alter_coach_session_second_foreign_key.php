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
        //coaches_sessions_coach_id_foreign
        Schema::table('coach_session', function (Blueprint $table) {
            $table->dropForeign('coaches_sessions_coach_id_foreign');
            $table->foreign("coach_id")->references('id')->on('coaches')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //kill yourself
    }
};
