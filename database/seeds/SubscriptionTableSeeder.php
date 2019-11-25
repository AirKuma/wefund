<?php

use Illuminate\Database\Seeder;

class SubscriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=49;$i++){

            DB::table('subscriptions')->insert([
                'subscriptable_id' => $i,
                'subscriptable_type' => 'App\Billboard',
                'allow' => '0',
                'user_id' => '2',
            ]);

            DB::table('subscriptions')->insert([
                'subscriptable_id' => $i,
                'subscriptable_type' => 'App\Billboard',
                'allow' => '0',
                'user_id' => '3',
            ]);

            DB::table('subscriptions')->insert([
                'subscriptable_id' => $i,
                'subscriptable_type' => 'App\Billboard',
                'allow' => '0',
                'user_id' => '4',
            ]);

        }
    }
}
