<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        User::create([
        	'name'  => 'Admin',
            'email' => 'saidabdulsalam5@gmail.com',
        	'role_id' => 1,
        	'password' => bcrypt('admin')
        ]);
    }
}
