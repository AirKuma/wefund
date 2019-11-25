<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'firstname' => 'System',
            'lastname' => 'Loyaus ',
            'email' => 'system@loyaus.com',
            'password'=>Hash::make('123'),
            'username' => 'system',
            'gender' => '1',
            'actived' => '1',
            'college_id' =>'1',
            'major_id' => '47',
            'permission' => '1',
        ]);

        DB::table('users')->insert([
            'firstname' => 'Administer',
            'lastname' => 'Loyaus ',
            'email' => 'administer@loyaus.com',
            'password'=>Hash::make('123'),
            'username' => 'administer',
            'gender' => '0',
            'actived' => '1',
            'college_id' =>'1',
            'major_id' => '47',
            'permission' => '1',
        ]);

        DB::table('users')->insert([
            'firstname' => 'Administer2',
            'lastname' => 'Loyaus ',
            'email' => 'loyaus@loyaus.com',
            'password'=>Hash::make('123'),
            'username' => 'loyaus',
            'gender' => '1',
            'actived' => '1',
            'college_id' =>'1',
            'major_id' => '47',
            'permission' => '1',
        ]);

        DB::table('users')->insert([
            'firstname' => 'Administer3',
            'lastname' => 'Loyaus ',
            'email' => 'admin@loyaus.com',
            'password'=>Hash::make('123'),
            'username' => 'admin',
            'gender' => '0',
            'actived' => '1',
            'college_id' =>'1',
            'major_id' => '47',
            'permission' => '1',
        ]);

        DB::table('users')->insert([
            'firstname' => 'Administer4',
            'lastname' => 'Loyaus ',
            'email' => '555@loyaus.com',
            'password'=>Hash::make('123'),
            'gender' => '1',
            'actived' => '1',
            'college_id' =>'1',
            'major_id' => '47',
            'permission' => '1',
        ]);

        DB::table('users')->insert([
            'firstname' => 'Administer5',
            'lastname' => 'Loyaus ',
            'email' => '666@loyaus.com',
            'password'=>Hash::make('123'),
            'gender' => '0',
            'actived' => '1',
            'college_id' =>'1',
            'major_id' => '47',
            'permission' => '1',
        ]);

        DB::table('users')->insert([
            'firstname' => 'Administer6',
            'lastname' => 'Loyaus ',
            'email' => '777@loyaus.com',
            'password'=>Hash::make('123'),
            'gender' => '1',
            'actived' => '1',
            'college_id' =>'1',
            'major_id' => '47',
            'permission' => '1',
        ]);

        DB::table('users')->insert([
            'firstname' => 'Administer7',
            'lastname' => 'Loyaus ',
            'email' => '888@loyaus.com',
            'password'=>Hash::make('123'),
            'gender' => '0',
            'actived' => '1',
            'college_id' =>'1',
            'major_id' => '47',
            'permission' => '1',
        ]);

    }
}
