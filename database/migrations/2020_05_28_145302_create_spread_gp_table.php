<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpreadGpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('spread_gp');
        Schema::create('spread_gp', function(Blueprint $table){
            $table->increments('id',255);
            $table->integer('students_id')->unsigned()->index();
            $table->integer('NSS');
            $table->integer('RCU');
            $table->integer('ECU');
            $table->integer('CP');
            $table->float('GPA');
            $table->integer('TRCU');
            $table->integer('TECU');
            $table->integer('TCP');
            $table->integer('TDCP');
            $table->float('PCGPA');
            $table->float('CGPA');
            $table->string('COs',255);
            $table->integer('year');
            $table->integer('semester');
            $table->integer('department_id')->unsigned()->index();
            $table->integer('level_id')->unsigned()->index();
            $table->integer('ncount');

            //$table->string('result_token',100)->unsigned()->index();
            //$table->boolean('status');
        });

        Schema::table('spread_gp', function(Blueprint $table){
            $table->foreign('students_id')->references('id')->on('students')
            ->onDelete('cascade')
            ->onUpdate('cascade');

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
        //
        Schema::dropIfExists('spread_gp');
    }
}
