<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('logs');
        Schema::create('logs', function(Blueprint $table){
            $table->increments('id',255);
            $table->integer('user_id')->unsigned()->index();
            $table->string('type',200);
            $table->string('description',255);
            $table->timestamp('action_date');
        });

        Schema::table('logs', function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('restrict')
            ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('logs');
    }
}
