<?php

use Illuminate\Database\Seeder;

class LevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('level')->delete();
        DB::table('level')->insert([
            'level'  => '100',
            'status'  => 0
        ]);
        DB::table('level')->insert([
            'level'  => '200',
            'status'  => 0
        ]);
        DB::table('level')->insert([
            'level'  => '300',
            'status'  => 0
        ]);
        DB::table('level')->insert([
            'level'  => '400',
            'status'  => 0
        ]);
        DB::table('level')->insert([
            'level'  => '500',
            'status'  => 0
        ]);
        DB::table('level')->insert([
            'level'  => 'spilling',
            'status'  => 0
        ]);
    }
}
