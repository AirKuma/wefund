<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Repositories\Contracts\UserRepositoryInterface;
use Repositories\Contracts\NotificationRepositoryInterface;
use App\Item;
use App\User;

class NotifyItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:item';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $users, NotificationRepositoryInterface $notifications)
    {
        parent::__construct();
        $this->notifications = $notifications;
        $this->users = $users;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $items = Item::where('end_time','<' ,Carbon::now())->where('disabled',0)->where('notification', 0)->get();

        if($items->count() > 0){
            foreach($items as $item){

                // if($item->){
                //     $item->users()->count();
                // }

                if($item->users()->count() > 0 && $item->free==0){
                    $item_owner = $item->user()->get();
                    $item_winner = $this->users->find($item->users()->orderBy('item_user.created_at', 'desc')->first()->pivot->user_id);
                   
                    if($item->type == 0){
                        $item_content_owner = '恭喜你! 你的項目「'. $item->name . '」成功賣出, 請盡快聯絡買家!';
                        $item_content_winner = '恭喜你! 你己成功投得項目「'. $item->name . '」, 請盡快聯絡賣家!';
                        $link = '/auction/bid/item/'. $item->id;  
                    }elseif ($item->type == 1) {
                        $item_content_owner = '恭喜你! 你的項目「'. $item->name . '」成功投出, 請盡快聯絡對方!';
                        $item_content_winner = '恭喜你! 你己成功投得項目「'. $item->name . '」, 請盡快聯絡對方!';
                        $link = '/auction/seek/item/'. $item->id; 
                    }

                    $this->notifications->postNotificationToUsers($item_owner, $item_content_owner, $link, $item->id ,'App\Item');

                    $this->notifications->postNotificationToUsers($item_winner, $item_content_winner, $link, $item->id ,'App\Item');

                    // \App\Notification::insert(array('user_id'=> $item_winner->id, 'sender_id' => 1, 'notificatable_id' => $item->id , 'notificatable_type' => 'App\Item' , 'content'=> $item_content_winner , 'link' => $link, 'sent_at' => Carbon::now(), 'created_at' => Carbon::now() , 'updated_at' => Carbon::now()));

                    $item->notification = 1;
                    $item->save();

                    
                }elseif ($item->users()->count() == 0) {
                    $item_owner = $item->user()->get();
                    if($item->repost < 3){
                        $item_content_owner = '你的項目「'. $item->name . '」的時間己經結束，可以到「我的項目」中, 設定重新上架。';

                    }elseif ($item->repost > 2) {
                        $item_content_owner = '你的項目「'. $item->name . '」的時間己經結束。';
                    }
                    if($item->type == 0){
                        $link = '/auction/bid/admin';
                    }else{
                        $link = '/auction/seek/admin';
                    }
                    
                    $this->notifications->postNotificationToUsers($item_owner, $item_content_owner, $link, $item->id ,'App\Item');
                    $item->notification = 1;
                    $item->save();
                }else{
                    $item->notification = 1;
                    $item->save();
                }
                 // $this->info($item->users()->count());
            }
             $this->info('Notify '.$items->count() . ' Items');
           
             \Log::info(''. \Carbon\Carbon::now(). 'Notify '.$items->count() . ' Items');
        }

          //\Log::info('Job '. \Carbon\Carbon::now());
    }
}
