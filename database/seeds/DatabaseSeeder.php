<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
         $this->call(FacultyTableSeeder::class);
         $this->call(DepartmentTableSeeder::class);
         $this->call(GradeTableSeeder::class);
         $this->call(LevelTableSeeder::class);
         $this->call(RoleTableSeeder::class);
         $this->call(SemesterTableSeeder::class);
         $this->call(SessionTableSeeder::class);
         $this->call(UserTableSeeder::class);
    }
}
