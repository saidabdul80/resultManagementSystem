<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentRegisteredCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('students_registered_courses');
         Schema::create('students_registered_courses', function(Blueprint $table){
            $table->increments('id',255);
            $table->integer('student_id')->unsigned()->index();
            $table->integer('course_id')->unsigned()->index();
            $table->integer('session_id')->unsigned()->index();
            $table->integer('level_id')->unsigned()->index();
            $table->integer('semester');
            $table->integer('created_by_user_id')->unsigned()->index();;
            $table->boolean('status');
        });

        Schema::table('students_registered_courses', function(Blueprint $table){
            $table->foreign('created_by_user_id')->references('id')->on('users')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('course_id')->references('id')->on('courses')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('session_id')->references('id')->on('sessions')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('student_id')->references('id')->on('students')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('level_id')->references('id')->on('level')
            ->onDelete('cascade')
            ->onUpdate('cascade');
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
         Schema::dropIfExists('students_registered_courses');
    }
}
