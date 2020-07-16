<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemesterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('semesters');
        Schema::create('semesters', function(Blueprint $table){
            $table->increments('id',255);
            $table->integer('semester_id');
            $table->string('semester',90);
            $table->integer('c_set');
            $table->timestamp('created_on');
            $table->boolean('status');
        });

        Schema::table('semesters', function(Blueprint $table){
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
        Schema::dropIfExists('semesters');
    }
}
