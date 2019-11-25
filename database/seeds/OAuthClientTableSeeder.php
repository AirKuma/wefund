<?php

use Illuminate\Database\Seeder;

class OAuthClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\OAuthClient::create([
			'id' => 'gsaddfdsfassgg322431',
			'secret' => 'sfdsgfd423tergfdgfadg',
			'name' => 'Android'
		]);

		\App\OAuthClient::create([
			'id' => 'gsasdffdsfassgg322431',
			'secret' => 'sfdddgfd423tergfdgfadg',
			'name' => 'Website'
		]);
    }
}
