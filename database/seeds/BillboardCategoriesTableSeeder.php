<?php

use Illuminate\Database\Seeder;

class BillboardCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('billboard_categories')->insert([
            'name' => '公告',
            'billboard_id' => '1',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '資訊',
            'billboard_id' => '1',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '活動',
            'billboard_id' => '1',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '建議',
            'billboard_id' => '1',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '提問',
            'billboard_id' => '1',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '資訊',
            'billboard_id' => '2',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '社團',
            'billboard_id' => '2',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '活動',
            'billboard_id' => '2',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '好玩',
            'billboard_id' => '2',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '好康',
            'billboard_id' => '2',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '美食',
            'billboard_id' => '2',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '靠北',
            'billboard_id' => '2',
        ]);

        DB::table('billboard_categories')->insert([
            'name' => '失物招領',
            'billboard_id' => '2',
        ]);

        for($i=3;$i<=49;$i++){

            DB::table('billboard_categories')->insert([
                'name' => '資訊',
                'billboard_id' => $i,
            ]);

            DB::table('billboard_categories')->insert([
                'name' => '活動',
                'billboard_id' => $i,
            ]);
        }
    }
}
