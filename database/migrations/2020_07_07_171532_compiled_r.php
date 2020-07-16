<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompiledR extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExist('compiled_r');
        Schema::create('compiled_r', function(Blueprint $table)){
            $table->increments('id',255); 
            $table->string('detail',255);
            $table->integer('session');
            $table->integer('semester');
            $table->integer('level');
            $table->integer('department');
            
            $table->timestamps();
            $table->boolean('status');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
