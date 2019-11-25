<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Http\Request;

use App\Fbmessenger;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\Contracts\ItemRepositoryInterface;
use Repositories\Contracts\NotificationRepositoryInterface;

class FbmessengerController extends Controller
{
    public function __construct(ItemRepositoryInterface $items,NotificationRepositoryInterface $notifications){
        $this->items = $items;
        $this->notifications = $notifications;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postFBmessenger(Request $request)
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $sender = $input['entry'][0]['messaging'][0]['sender']['id'];

        if(!empty($input['entry'][0]['messaging'][0]['message']['text'])){
            $messagetext = $input['entry'][0]['messaging'][0]['message']['text'];   
        }       

        if(!empty($input['entry'][0]['messaging'][0]['postback']['payload'])){
            $payload = $input['entry'][0]['messaging'][0]['postback']['payload'];

            if($payload=="get_start" && $sender!=null && Fbmessenger::where('sender_id',$sender)->first()==null){
                $text = $this->createfbmessenger($sender);
            }elseif($payload=="yes"){
                $fbmessenger = Fbmessenger::where('sender_id',$sender)->first();
                if($fbmessenger!=null){
                    $text = $this->is_subscriptionfbmessenger($fbmessenger);
                }else{
                    $text = $this->createfbmessenger($sender);
                }
            }

        }

        if((!empty($payload) && $payload=='setting') || (!empty($messagetext) && $messagetext=='setting')){
                if(Fbmessenger::where('sender_id',$sender)->first()!=null && Fbmessenger::where('sender_id',$sender)->first()->is_subscription==1)
                      $text = '😢 是否要取消訂閱？';
                else
                      $text = '😃 是否要訂閱？';    

                $jsonData = '{
                    "recipient":{
                        "id":"' . $sender . '"
                    }, 
                    "message":{
                        "attachment":{
                          "type":"template",
                          "payload":{
                            "template_type":"button",
                            "text":"' . $text . '",
                            "buttons":[
                              {
                                "type":"postback",
                                "title":"👍 是",
                                "payload":"yes"
                              },
                              {
                                "type":"postback",
                                "title":"👎 否",
                                "payload":"cancel"
                              }
                            ]
                          }
                        }
                        
                    },
                }';
            }elseif(!empty($payload) && $payload=='yes'){
                $jsonData = '{
                    "recipient":{
                        "id":"' . $sender . '"
                    }, 
                    "message":{
                        "text":"' . $text . '"
                    }
                }';
            }elseif((!empty($payload) && ($payload=="get_start" || $payload=="help")) || (!empty($messagetext) && $messagetext=='help')){   
                if(!empty($payload) && $payload=="get_start")
                  $text = "您好！感謝您使用我們的服務。訂閱Loyaus服務，我們將於每晚10點為您送上當日拍賣的項目。您可以從輸入對話框左側的菜單或輸入「help」進行操作。";
                else
                  $text = "請點選下列按鈕進行操作：";

                $jsonData = '{
                    "recipient":{
                        "id":"' . $sender . '"
                    }, 
                    "message":{
                        "attachment":{
                          "type":"template",
                          "payload":{
                            "template_type":"button",
                            "text":"'.$text.'",
                            "buttons":[
                              {
                                "type":"postback",
                                "title":"💫 最新拍賣",
                                "payload":"new"
                              },
                              {
                                "type":"postback",
                                "title":"🛠 訂閱設置",
                                "payload":"setting"
                              },
                              {
                                "type":"web_url",
                                "title":"Loyaus 網頁",
                                "url":"loyaus.com"
                              }
                            ]
                          }
                        }
                        
                    },
                }';
            }elseif((!empty($payload) && $payload=='new') || (!empty($messagetext) && $messagetext=='new')){
                $items = $this->items->where('disabled','0')->where('type','0')->where('end_time','>',Carbon::now())->orderby('created_at','desc')->get()->take(5);

                $elements = '';
                foreach($items as $key => $item) {
                    $title = $item->name;
                    $item_url = route('get.auction.item.show', ['auction' =>  $item->type==0 ? 'bid':'seek','id' => $item->id]);
                    $image_url = \Request::root()."/images/auctions/thumbs/".$item->albums()->first()->images()->first()->file_name;
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
                $jsonData = '{
                    "recipient":{
                        "id":"' . $sender . '"
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
            }

        if(!empty($jsonData))
            $send = $this->notifications->sendFbmessenger($jsonData);

    }

    public function createfbmessenger($sender)
    {

            $fbmessenger = new Fbmessenger;
            $fbmessenger->sender_id = $sender;
            $fbmessenger->is_subscription = 1;
            $fbmessenger->college_acronym = 'fju';
            $fbmessenger->send_at = Carbon::now()->addDays(-1);
            $fbmessenger->save();
            $text = '已成功訂閱！';
            return $text;
    }

    public function is_subscriptionfbmessenger($fbmessenger)
    {

            if($fbmessenger->is_subscription == 1){
                $fbmessenger->is_subscription = 0;
                $text = '已取消訂閱！';
            }
            else{
                $fbmessenger->is_subscription = 1;
                $text = '已成功訂閱！';
            }
            $fbmessenger->save();
            return $text;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
