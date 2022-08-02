<?php

use Illuminate\Database\Seeder;
use App\User;

class FacultyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('faculties')->delete();
        DB::table('faculties')->insert([
        	'faculty'  => 'Test Faculty 1',
        	'faculty_abbr' => 'TF',
            'status' => '0'
        ]);
    }
}
