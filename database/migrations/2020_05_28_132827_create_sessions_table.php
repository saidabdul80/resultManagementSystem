<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('sessions');
        Schema::create('sessions', function(Blueprint $table){
            $table->increments('id',255);
            $table->string('session',90);
            $table->integer('c_set');
            $table->timestamp('created_on');
            $table->boolean('status');
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
        Schema::dropIfExists('sessions');
    }
}
