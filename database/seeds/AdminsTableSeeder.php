<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=49;$i++){

            DB::table('admins')->insert([
                'adminable_id' => $i,
                'adminable_type' => 'App\Billboard',
                'user_id' => '2',
            ]);

            DB::table('admins')->insert([
                'adminable_id' => $i,
                'adminable_type' => 'App\Billboard',
                'user_id' => '3',
            ]);

            DB::table('admins')->insert([
                'adminable_id' => $i,
                'adminable_type' => 'App\Billboard',
                'user_id' => '4',
            ]);
        }
    }
}
