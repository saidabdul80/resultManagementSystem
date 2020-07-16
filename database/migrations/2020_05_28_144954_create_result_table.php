<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('results');
        Schema::create('results', function(Blueprint $table){
            $table->increments('id',255);
            $table->integer('students_id')->unsigned()->index();
            $table->integer('course_id')->unsigned()->index();
            $table->integer('session_id')->unsigned()->index();
            $table->integer('final_score');
            $table->string('grades',5);
            $table->integer('lecturer_id')->unsigned()->index();
            $table->string('result_token');
            $table->boolean('status');
        });

        Schema::table('results', function(Blueprint $table){
            $table->foreign('students_id')->references('id')->on('students')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('course_id')->references('id')->on('courses')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('session_id')->references('id')->on('sessions')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('lecturer_id')->references('id')->on('lecturers')
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
        Schema::dropIfExists('results');
    }
}
