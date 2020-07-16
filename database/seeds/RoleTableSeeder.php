<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->delete();
        DB::table('roles')->insert([
            'role'  => 'admin',
            'role_code'  => '',
            //'created_on'  => date('Y-m-d'),
            'status'  => 0
        ]);
        DB::table('roles')->insert([
            'role'  => 'lecturer',
            'role_code'  => '',
            //'created_on'  => date('Y-m-d'),
            'status'  => 0
        ]);
        DB::table('roles')->insert([
            'role'  => 'examiner',
            'role_code'  => '',
            //'created_on'  => date('Y-m-d'),
            'status'  => 0
        ]);
        DB::table('roles')->insert([
            'role'  => 'faculty',
            'role_code'  => '',
            //'created_on'  => date('Y-m-d'),
            'status'  => 0
        ]);
        DB::table('roles')->insert([
            'role'  => 'user',
            'role_code'  => '',
            //'created_on'  => date('Y-m-d'),
            'status'  => 0
        ]);
    }
}
