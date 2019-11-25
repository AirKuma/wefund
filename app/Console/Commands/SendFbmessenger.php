<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Item;
use App\Fbmessenger;
use Repositories\Contracts\ItemRepositoryInterface;
use Repositories\Contracts\NotificationRepositoryInterface;

class SendFbmessenger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:fbmessenger';

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
    public function __construct(ItemRepositoryInterface $items,NotificationRepositoryInterface $notifications)
    {
        parent::__construct();
        $this->items = $items;
        $this->notifications = $notifications;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $senders = Fbmessenger::where('is_subscription',1)->where('send_at','<',Carbon::today())->get();
        $items = $this->items->where('disabled','0')->where('type','0')->where('end_time','>',Carbon::now())->where('start_time','>',Carbon::now()->addDays(-1))->where('start_time','<=',Carbon::now())->orderby('created_at','desc')->get();

        if($items->count()!=0 && $senders->count()!=0){

            $elements = '';
            foreach($items as $key => $item) {
                $title = $item->name;
                $item_url = "https://www.loyaus.com/auction/bid/item/".$item->id;
                $image_url = Cloudder::secureShow($item->albums()->first()->images()->first()->file_name, ["width"=>300, "height"=>400, "crop"=>"limit"]);
                if($item->free==1){
                    $price = "免費";
                }
                elseif(count($item->users()->get()) == 0){
                    $price = "目前出價：NT$".$item->price;
                }elseif($item->type==0){ 
                    $price = "目前出價：NT$".$item->users()->orderBy('item_user.price', 'desc')->first()->pivot->price;
                }else{
                    $price = "目前出價：NT$".$item->users()->orderBy('item_user.price', 'asc')->first()->pivot->price;
                }
                $elements = $elements.
                '{
                        "title":"'.$title.'",
                        "item_url":"'.$item_url.'",
                        "image_url":"'.$image_url.'",
                        "subtitle":"'.$price.'",
                        "buttons":[
                          {
                            "type":"web_url",
                            "url":"'.$item_url.'",
                            "title":"更多資訊"
                          },             
                        ]
                },';
            }

            foreach ($senders as $key => $sender) {
                $sender_id = $sender->sender_id;

                $jsonData = '{
                    "recipient":{
                        "id":"' . $sender_id . '"
                    }, 
                    "message":{
                        "attachment":{
                         
                            "type":"template",
                            "payload":{
                                "template_type":"generic",
                                "elements":[
                                  '.$elements.'
                                ]
                           }

                        }
                    }
                }';

                // $jsonDatatext = '{
                //     "recipient":{
                //         "id":"' . $sender_id . '"
                //     }, 
                //     "message":{
                //         "text":"今日拍賣項目已為您送上，感謝您的關注！"
                //     }
                // }';

                // if(!empty($jsonDatatext))
                //     $sendtext = $this->notifications->sendFbmessenger($jsonDatatext);
                if(!empty($jsonData))
                    $send = $this->notifications->sendFbmessenger($jsonData);

                if($send){
                  $sender->send_at = Carbon::now();
                  $sender->save();
              }
            }
        }

    }
}
