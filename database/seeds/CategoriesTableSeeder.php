<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => '學校課本',
            'en_name' => 'book',
            'type' => 'bid_items',
        ]);

        DB::table('categories')->insert([
            'name' => '圖書影音',
            'en_name' => 'CD',
            'type' => 'bid_items',
        ]);

        DB::table('categories')->insert([
            'name' => '服飾配件',
            'en_name' => 'clothing',
            'type' => 'bid_items',
        ]);

        DB::table('categories')->insert([
            'name' => '電腦網絡',
            'en_name' => 'computer',
            'type' => 'bid_items',
        ]);

        DB::table('categories')->insert([
            'name' => '數碼通訊',
            'en_name' => 'digital',
            'type' => 'bid_items',
        ]);

        DB::table('categories')->insert([
            'name' => '家具家電',
            'en_name' => 'furniture',
            'type' => 'bid_items',
        ]);

        DB::table('categories')->insert([
            'name' => '生活用品',
            'en_name' => 'life',
            'type' => 'bid_items',
        ]);

        DB::table('categories')->insert([
            'name' => '樂器用品',
            'en_name' => 'instrument',
            'type' => 'bid_items',
        ]);


        DB::table('categories')->insert([
            'name' => '其他',
            'en_name' => 'other',
            'type' => 'bid_items',
        ]);

        DB::table('categories')->insert([
            'name' => '課本',
            'en_name' => 'book',
            'type' => 'seek_items',
        ]);

        DB::table('categories')->insert([
            'name' => '室友',
            'en_name' => 'roommate',
            'type' => 'seek_items',
        ]);

        DB::table('categories')->insert([
            'name' => '工作',
            'en_name' => 'work',
            'type' => 'seek_items',
        ]);

        DB::table('categories')->insert([
            'name' => '旅伴',
            'en_name' => 'companion',
            'type' => 'seek_items',
        ]);

        DB::table('categories')->insert([
            'name' => '補習',
            'en_name' => 'cram',
            'type' => 'seek_items',
        ]);

        DB::table('categories')->insert([
            'name' => '其他',
            'en_name' => 'other',
            'type' => 'seek_items',
        ]);
    }
}
