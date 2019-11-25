<?php

use Illuminate\Database\Seeder;

class MajorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('majors')->insert([
            'name' => '中國文學系',
            'acronym' => 'chinese',
        ]);

        DB::table('majors')->insert([
            'name' => '歷史學系',
            'acronym' => 'history',
        ]);

        DB::table('majors')->insert([
            'name' => '哲學系',
            'acronym' => 'philosophy',
        ]);

        DB::table('majors')->insert([
            'name' => '企業管理學系',
            'acronym' => 'mba',
        ]);

        DB::table('majors')->insert([
            'name' => '金融與國際企業學系',
            'acronym' => 'fib',
        ]);

        DB::table('majors')->insert([
            'name' => '圖書資訊學系',
            'acronym' => 'lins',
        ]);

        DB::table('majors')->insert([
            'name' => '影像傳播學系',
            'acronym' => 'commarts',
        ]);

        DB::table('majors')->insert([
            'name' => '新聞傳播學系',
            'acronym' => 'jcs',
        ]);

        DB::table('majors')->insert([
            'name' => '廣告傳播學系',
            'acronym' => 'adpr',
        ]);

        DB::table('majors')->insert([
            'name' => '體育學系',
            'acronym' => 'phed',
        ]);

        DB::table('majors')->insert([
            'name' => '電機工程學系',
            'acronym' => 'ee',
        ]);

        DB::table('majors')->insert([
            'name' => '英國語文學系',
            'acronym' => 'english',
        ]);

        DB::table('majors')->insert([
            'name' => '法國語文學系',
            'acronym' => 'fren',
        ]);

        DB::table('majors')->insert([
            'name' => '西班牙語文學系',
            'acronym' => 'span',
        ]);

        DB::table('majors')->insert([
            'name' => '日本語文學系',
            'acronym' => 'jp',
        ]);
        
        DB::table('majors')->insert([
            'name' => '義大利語文學系',
            'acronym' => 'italy',
        ]);

        DB::table('majors')->insert([
            'name' => '德語語文學系',
            'acronym' => 'de',
        ]);
        
        DB::table('majors')->insert([
            'name' => '數學系',
            'acronym' => 'math',
        ]);

        DB::table('majors')->insert([
            'name' => '化學系',
            'acronym' => 'ch',
        ]);
        
        DB::table('majors')->insert([
            'name' => '心理學系',
            'acronym' => 'psy',
        ]);

        DB::table('majors')->insert([
            'name' => '織品服裝學系',
            'acronym' => 'tc',
        ]);
        
        DB::table('majors')->insert([
            'name' => '資訊工程學系',
            'acronym' => 'csie',
        ]);

        DB::table('majors')->insert([
            'name' => '生命科學系',
            'acronym' => 'bio',
        ]);
        
        DB::table('majors')->insert([
            'name' => '物理學系',
            'acronym' => 'phy',
        ]);

        DB::table('majors')->insert([
            'name' => '餐旅管理學系',
            'acronym' => 'rhim',
        ]);
        
        DB::table('majors')->insert([
            'name' => '兒童與家庭學系',
            'acronym' => 'cfs',
        ]);

        DB::table('majors')->insert([
            'name' => '社會學系',
            'acronym' => 'soci',
        ]);

        DB::table('majors')->insert([
            'name' => '社會工作學系',
            'acronym' => 'socialwork',
        ]);

        DB::table('majors')->insert([
            'name' => '經濟學系',
            'acronym' => 'economics',
        ]);

        DB::table('majors')->insert([
            'name' => '法律學系',
            'acronym' => 'laws',
        ]);

        DB::table('majors')->insert([
            'name' => '財經法律學系',
            'acronym' => 'financelaw',
        ]);

        DB::table('majors')->insert([
            'name' => '會計學系',
            'acronym' => 'acct',
        ]);

        DB::table('majors')->insert([
            'name' => '資訊管理學系',
            'acronym' => 'im',
        ]);

        DB::table('majors')->insert([
            'name' => '統計資訊學系',
            'acronym' => 'stat',
        ]);

        DB::table('majors')->insert([
            'name' => '音樂學系',
            'acronym' => 'music',
        ]);

        DB::table('majors')->insert([
            'name' => '應用美術學系',
            'acronym' => 'aart',
        ]);

        DB::table('majors')->insert([
            'name' => '景觀設計學系',
            'acronym' => 'landscape',
        ]);

        DB::table('majors')->insert([
            'name' => '食品科學系',
            'acronym' => 'fs',
        ]);

        DB::table('majors')->insert([
            'name' => '營養科學系',
            'acronym' => 'ns',
        ]);

        DB::table('majors')->insert([
            'name' => '宗教學系',
            'acronym' => 'rsd',
        ]);

        DB::table('majors')->insert([
            'name' => '護理學系',
            'acronym' => 'nursing',
        ]);

        DB::table('majors')->insert([
            'name' => '公共衛生學系',
            'acronym' => 'medph',
        ]);

        DB::table('majors')->insert([
            'name' => '醫學系',
            'acronym' => 'med',
        ]);

        DB::table('majors')->insert([
            'name' => '臨床心理學系',
            'acronym' => 'ideafun',
        ]);

        DB::table('majors')->insert([
            'name' => '職能治療學系',
            'acronym' => 'ot',
        ]);

        DB::table('majors')->insert([
            'name' => '呼吸治療學系',
            'acronym' => 'drt',
        ]);

        DB::table('majors')->insert([
            'name' => '其他系',
            'acronym' => 'other',
        ]);

    }
}
