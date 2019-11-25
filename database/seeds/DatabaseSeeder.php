<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

            $this->call(CollegesTableSeeder::class);
            $this->call(MajorsTableSeeder::class);
            $this->call(CategoriesTableSeeder::class);
            $this->call(UsersTableSeeder::class);
            $this->call(BillboardsTableSeeder::class);
            $this->call(SubscriptionTableSeeder::class);
            $this->call(AdminsTableSeeder::class);
            $this->call(BillboardCategoriesTableSeeder::class);
            $this->call(OAuthClientTableSeeder::class);

        Model::reguard();
    }
}
