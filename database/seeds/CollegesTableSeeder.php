<?php

use Illuminate\Database\Seeder;

class CollegesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('colleges')->insert([
            'name' => '輔仁大學',
            'acronym' => 'fju',
            'email' => '@mail.fju.edu.tw',
            'description' => 'Description',
            'address' => '24205 新北市新莊區中正路510號',
            'phone' => '(02) 29052000',
            'group_id' => '569806093039878',
        ]);
/*
        DB::table('colleges')->insert([
            'name' => '台灣大學',
            'acronym' => 'ntu',
            'email' => '@mail.ntu.edu.tw',
            'description' => 'Description',
            'address' => '24205 新北市新莊區中正路510號',
            'phone' => '(02) 29052000',
            'group_id' => '980383791993468',
        ]);*/



    }
}
