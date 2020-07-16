<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLecturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('lecturers');
        Schema::create('lecturers', function(Blueprint $table){
            $table->increments('id',255);
            $table->string('salute',20);
            $table->string('first_name',100);
            $table->string('surname',100);
            $table->string('lecture_ID',100);
            $table->string('phone',15);
            $table->string('email',192)->unique();
            $table->string('country',192);
            $table->string('address',192);
            $table->string('state',192);
            $table->string('nxt_of_kin_name',100);
            $table->string('nxt_of_kin_phone',15);
            $table->string('nxt_of_kin_address',200);
            $table->string('lga',100);
            $table->integer('department_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->boolean('status');
        });

        Schema::table('lecturers', function(Blueprint $table){

            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('department_id')->references('id')->on('departments')
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
        Schema::dropIfExists('lecturers');
        //
    }
}
