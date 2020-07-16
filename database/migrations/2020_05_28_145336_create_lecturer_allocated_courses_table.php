<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLecturerAllocatedCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('lecturer_allocated_courses');
        Schema::create('lecturer_allocated_courses', function(Blueprint $table){
            $table->increments('id',255);
            $table->integer('lecturer_id')->unsigned()->index();
            $table->integer('course_id')->unsigned()->index();
            $table->integer('session_id')->unsigned()->index();
            $table->integer('department_id')->unsigned()->index();
            $table->integer('created_by_user_id');
            $table->timestamp('created_on');
            $table->boolean('status');
        });

        Schema::table('lecturer_allocated_courses', function(Blueprint $table){
          

            $table->foreign('course_id')->references('id')->on('courses')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('session_id')->references('id')->on('sessions')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('lecturer_id')->references('id')->on('lecturers')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('department_id')->references('id')->on('departments')
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
        Schema::dropIfExists('lecturer_allocated_courses');
    }
}
