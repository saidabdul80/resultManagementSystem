<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('result_file');
        Schema::create('result_file', function(Blueprint $table){
            $table->increments('id',255);
            $table->integer('lecturer_id')->unsigned()->index();
            $table->string('result_token',100)->unique();
            $table->integer('course_id')->unsigned()->index();
            $table->integer('department_id')->unsigned()->index();
            $table->integer('session_id')->unsigned()->index();
            $table->integer('semester');
            $table->integer('examiner');
            $table->integer('faculty_examiner');
            $table->integer('senate');
            $table->integer('result_confirm_by_lect');
            $table->boolean('status');
        });

        Schema::table('result_file', function(Blueprint $table){
            $table->foreign('department_id')->references('id')->on('departments')
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
        Schema::dropIfExists('result_file');
    }
}
