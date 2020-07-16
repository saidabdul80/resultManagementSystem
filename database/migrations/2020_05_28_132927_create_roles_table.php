<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('roles');
        
         Schema::create('roles', function(Blueprint $table){
            $table->increments('id',255);
            $table->string('role',90);
            $table->string('role_code',90);
            $table->timestamp('created_at');
            $table->char('status')->default(0);
        });
         Schema::table('roles', function(Blueprint $table){
            $table->timestamp('updated_at')->nullable();

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
        Schema::dropIfExists('roles');
    }
}
