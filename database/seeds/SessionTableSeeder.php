<?php

use Illuminate\Database\Seeder;

class SessionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sessions')->delete();
        DB::table('sessions')->insert([
            'session'  => '2014/2015',
            'c_set' => 1,
            'status' => 0
        ]);
        DB::table('sessions')->insert([
            'session'  => '2015/2016',
            'c_set' => 0,
            'status' => 0
        ]);
    }
}
