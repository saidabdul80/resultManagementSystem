<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultTrendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('result_trend');
        Schema::create('result_trend', function(Blueprint $table){
            $table->increments('id',255);
            $table->string('passFail', 200);
            $table->integer('level_id')->unsigned()->index();
            $table->integer('semesters');
            $table->integer('session')->unsigned()->index();
            $table->integer('department')->unsigned()->index();
            $table->boolean('status');
        });

        Schema::table('result_trend', function(Blueprint $table){
            $table->foreign('level_id')->references('id')->on('level')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('session')->references('id')->on('sessions')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('department')->references('id')->on('departments')
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
        Schema::dropIfExists('result_trend');
    }
}
