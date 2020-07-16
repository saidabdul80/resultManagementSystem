<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFTimingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('f_timing');
        Schema::create('f_timing', function(Blueprint $table){
            $table->increments('id',255);
            $table->integer('faculty')->unsigned()->index();
            $table->integer('session')->unsigned()->index();
            $table->integer('semester');
            $table->date('startsT');
            $table->date('endT');
            $table->boolean('status');
        });

        Schema::table('f_timing', function(Blueprint $table){
            $table->foreign('faculty')->references('id')->on('faculty')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('session')->references('id')->on('sessions')
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
         Schema::dropIfExists('f_timing');
    }
}
