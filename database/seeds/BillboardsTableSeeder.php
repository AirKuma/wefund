<?php

use Illuminate\Database\Seeder;

class BillboardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('billboards')->insert([
            'name' => 'Loyaus',
            'description' => '這裡可以看到系統更新消息與使用說明，以及其他公告、資訊。'."\n".'如果大家有什麼問題或有什麼建議也可以在這裡分享喔！',
            'type' => '0',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'loyaus',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '輔仁大學',
            'description' => '這裡可以讓大家分享輔大的資訊，增加同學間的交流，讓大家更了解校園生活。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'fju',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '中國文學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'chinese',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '歷史學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'history',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '哲學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'philosophy',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '企業管理學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'mba',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '金融與國際企業學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'fib',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '圖書資訊學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'lins',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '影像傳播學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'commarts',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '新聞傳播學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'jcs',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '廣告傳播學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'adpr',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '體育學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'phed',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '電機工程學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'ee',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '英國語文學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'english',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '法國語文學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'fren',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '西班牙語文學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'span',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '日本語文學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'jp',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '義大利語文學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'italy',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '德語語文學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'de',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '數學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'math',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '化學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'ch',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '心理學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'psy',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '織品服裝學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'tc',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '資訊工程學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'csie',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '生命科學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'bio',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '物理學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'phy',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '餐旅管理學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'rhim',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '兒童與家庭學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'cfs',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '社會學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'soci',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '社會工作學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'socialwork',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '經濟學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'economics',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '法律學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'laws',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '財經法律學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'financelaw',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '會計學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'acct',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '資訊管理學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'im',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '統計資訊學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'stat',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '音樂學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'music',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '應用美術學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'aart',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '景觀設計學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'landscape',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '食品科學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'fs',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '營養科學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'ns',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '宗教學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'rsd',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '護理學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'nursing',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '公共衛生學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'medph',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '醫學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'med',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '臨床心理學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'ideafun',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '職能治療學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'ot',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '呼吸治療學系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'drt',
            'college_id' => '1',
        ]);

        DB::table('billboards')->insert([
            'name' => '其他系',
            'description' => '這裡大家可以分享系上的活動與資訊、增進交流。'."\n".'如有意，歡迎大家申請當版主。',
            'type' => '1',
            'anonymous' => '0',
            'adult' => '0',
            'domain' => 'other',
            'college_id' => '1',
        ]);

    }
}
