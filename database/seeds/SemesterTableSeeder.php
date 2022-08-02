<?php

use Illuminate\Database\Seeder;

class SemesterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('semesters')->delete();
        DB::table('semesters')->insert([
            'semester_id'  => 1,
            'semester'  => 'first semester',
            'c_set' => 1,
            'status' => 0
        ]);
        DB::table('semesters')->insert([
            'semester_id'  => 2,
            'semester'  => 'second semester',
            'c_set' => 0,
            'status' => 0
        ]);
    }
}
