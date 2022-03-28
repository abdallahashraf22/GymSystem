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
        Schema::table('coach_session', function (Blueprint $table) {
//            $table->foreignId('id')
//                ->change()
//                ->constrained('sessions')
//                ->onDelete('cascade');
            $table->dropForeign('coaches_sessions_session_id_foreign');
            $table->foreign("session_id")->references('id')->on('sessions')->cascadeOnDelete();
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
