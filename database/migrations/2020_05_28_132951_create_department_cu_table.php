<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentCuTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('department_cu');
        Schema::create('department_cu', function(Blueprint $table){
            $table->increments('id',255);
            $table->string('100L',90);
            $table->string('200L',90);
            $table->string('300L',90);
            $table->string('400L',90);
            $table->string('500L',90);
            $table->integer('semester')->unsigned();
        });

        Schema::table('department_cu', function(Blueprint $table){
            $table->integer('department_id')->unsigned()->index()->after('semester');
            $table->integer('session_id')->unsigned()->index()->after('semester');

            $table->foreign('session_id')->references('id')->on('sessions')
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
        //
        Schema::dropIfExists('department_cu');
    }
}
