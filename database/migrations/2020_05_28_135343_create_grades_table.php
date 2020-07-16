<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
          //
        Schema::dropIfExists('grades');
        Schema::create('grades', function(Blueprint $table){
            $table->increments('id',255);
            $table->string('name',90);
            $table->string('A',10);
            $table->string('B',10);
            $table->string('C',10);
            $table->string('D',10);
            $table->string('E',10);
            $table->string('F',10);
            $table->string('CO',1);
            $table->string('created_by',190);
            $table->timestamp('created_on');
            $table->integer('c_set');
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
        Schema::dropIfExists('grades');
    }
}
