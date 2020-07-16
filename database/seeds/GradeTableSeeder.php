<?php

use Illuminate\Database\Seeder;

class GradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
           DB::table('grades')->delete();
        DB::table('grades')->insert([
        	'name'  => 'default_grading_scale',
            'A' => '70',
            'B' => '60',
            'C' => '50',
            'D' => '45',
            'E' => '40',
            'F' => '0',
        	'CO' => 'E',
        	'created_by' => '1',
            'c_set' => 1,
            'status' => 0
        ]);
    }
}
