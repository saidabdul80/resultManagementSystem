<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('courses');

        Schema::create('courses', function(Blueprint $table){
            $table->increments('id',255);
            $table->string('course_code',90);
            $table->string('course_title',191);
            $table->string('course_description',555)->default('-');
            $table->integer('credit_unit')->unsigned();
            $table->integer('semester')->unsigned();        
            $table->timestamps();
            $table->boolean('status');
        });

        Schema::table('courses', function(Blueprint $table){
            $table->integer('level_id')->unsigned()->index()->after('credit_unit');
            $table->integer('department_id')->unsigned()->index()->after('credit_unit');

            $table->foreign('level_id')->references('id')->on('level')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('department_id')->references('id')->on('departments')
            ->onDelete('restrict')
            ->onUpdate('no action');
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('courses');
    }
}
