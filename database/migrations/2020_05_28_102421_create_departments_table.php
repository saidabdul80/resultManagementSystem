<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::dropIfExists('departments');
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id',255); 
            $table->string('department')->unique();
            $table->char('department_abbr')->unique();
            $table->boolean('status');
        });
        Schema::table('departments', function(Blueprint $table){
            $table->integer('faculty_id')->unsigned()->index();
            $table->foreign('faculty_id')->references('id')->on('faculties')
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
        Schema::dropIfExists('departments');
    }
}
