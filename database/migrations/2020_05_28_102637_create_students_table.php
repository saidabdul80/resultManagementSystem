<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('students');
         Schema::create('students', function (Blueprint $table){
            $table->increments('id',255); 
            $table->string('first_name');
            $table->string('surname');
            $table->string('other_name');
            $table->string('matric_number')->unique();
            $table->string('gender');
            $table->char('phone_number',15);
            $table->char('email',200)->unique();
            $table->string('country');
            $table->string('state_of_origin');
            $table->string('lga');
            $table->string('address');
            $table->string('nxt_of_kin_name');
            $table->string('nxt_of_kin_phone');
            $table->string('nxt_of_kin_address');
            $table->string('ME');
            $table->integer('department_id')->unsigned()->index();
            $table->integer('level_id')->unsigned()->index();
            $table->boolean('status');

            /*
            $table->foreign('department_id')
                ->references('id')->on('departments')
                ->onDelete('restrict');
            $table->foreign('level_id')
                ->references('id')->on('level')
                ->onDelete('restrict');
                */
            
            $table->foreign('department_id')->references('id')->on('departments')
            ->onDelete('restrict')
            ->onUpdate('no action');

            $table->foreign('level_id')->references('id')->on('level')
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
         Schema::dropIfExists('students');
    }
}
