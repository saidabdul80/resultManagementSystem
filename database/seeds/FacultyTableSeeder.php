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
        	'faculty'  => 'Natural Science',
        	'faculty_abbr' => 'Natural_sci',
            'status' => '0'
        ]);
    }
}
