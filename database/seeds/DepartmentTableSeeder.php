<?php

use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->delete();
        DB::table('departments')->insert([
        	'department'  => 'Computer Science',
            'department_abbr' => 'CSC',
            'faculty_id' => '1',
            'status' => '0'
        ]);
    }
}
