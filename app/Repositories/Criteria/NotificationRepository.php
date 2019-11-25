<?php
namespace Repositories\Criteria;
use Repositories\Contracts\NotificationRepositoryInterface;
use App\Notification;
use Auth;
use DB;
use Carbon\Carbon;
class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface {

	protected $modelName = 'App\Notification';

	public function postToFacebookGroup($message,$token,$link,$group_id)
	{

        //Force facebook to reload cache
        $reload_api_url = 'https://graph.facebook.com/?';
        $reload_data = 'id='. $link .'&scrape=true';
        $reload_ch = curl_init($reload_api_url);
        curl_setopt($reload_ch, CURLOPT_POST, 1);
        curl_setopt($reload_ch, CURLOPT_POSTFIELDS, $reload_data);
        curl_setopt($reload_ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($reload_ch, CURLOPT_HEADER, 0);
        curl_setopt($reload_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($reload_ch);

        // Post to Facebook Group
		$url = 'https://graph.facebook.com/v2.6/'.$group_id.'/feed?';
		$data =  'access_token='.$token.'&message='.$message.'&link='.$link;

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return curl_exec($ch);





    }

    public function sendEmail($view,$array,$email)
	{
	
        return \Mail::send($view,$array, function($message) use($email)
        {
            $message->from($email['from']);
            $message->to($email['email'],$email['name'])->subject($email['subject']);
        });;
    }

	public function postNotificationToUsers($users,$content,$link,$notificatable_id,$notificatable_type)
	{
		$notifications = array();

        foreach ($users as $user) {
            $sender_id = Auth::id();
            if(!$sender_id){
                $sender_id = 1;
            }

            array_push($notifications, 
                array('user_id'=> $user->id, 'sender_id' => $sender_id, 'notificatable_id' => $notificatable_id , 'notificatable_type' => $notificatable_type , 'content'=> $content ,'link' => $link, 'sent_at' => Carbon::now(),'created_at' => Carbon::now() , 'updated_at' => Carbon::now()))
            ;
        }

        Notification::insert($notifications);

        foreach ($users as $user) {
           $receiver = \App\User::find($user->id);
           $count = $receiver->notifications()->where('notificatable_type','App\Item')->where('is_read',0)->where('created_at','>',$receiver->read_notification_at)->count();
           event(new \App\Events\PushNotification($receiver, $count));
        }

        return 0;

    }

    public function postNotificationToUsersAPI($users,$content,$link,$notificatable_id,$notificatable_type)
    {
        $notifications = array();

        foreach ($users as $user) {
            $sender_id = app('Dingo\Api\Auth\Auth')->user()->id;
            if(!$sender_id){
                $sender_id = 1;
            }
           // $receiver = \App\User::find($user->id);
           // $count = $receiver->notifications()->where('notificatable_type','App\Item')->where('is_read',0)->where('created_at','>',$receiver->read_notification_at)->count();
           // event(new \App\Events\PushNotification($receiver, $count));


            array_push($notifications, 
                array('user_id'=> $user->id, 'sender_id' => $sender_id, 'notificatable_id' => $notificatable_id , 'notificatable_type' => $notificatable_type , 'content'=> $content ,'link' => $link, 'sent_at' => Carbon::now(),'created_at' => Carbon::now() , 'updated_at' => Carbon::now()))
            ;
        }

        Notification::insert($notifications);

        foreach ($users as $user) {
           $receiver = \App\User::find($user->id);
           $count = $receiver->notifications()->where('notificatable_type','App\Item')->where('is_read',0)->where('created_at','>',$receiver->read_notification_at)->count();
           event(new \App\Events\PushNotification($receiver, $count));
        }

        return 0;


    }


    public function fetch()
    {
        //return dd(Notification::find(18)->content);
    	// $first = DB::table('notifications')->orderBy('sent_at', 'desc')->union($notificationsGroup);
            $notifications = DB::table('notifications')->select('*')->orderBy('created_at', 'desc');
            //return dd($notifications->get());
        $notificationsGroup = DB::table(DB::raw("({$notifications->toSql()}) as notifications"))->select('notifications.content', 'notifications.id', 
            DB::raw('max(notifications.sent_at) as sent_at'), 
            DB::raw('min(notifications.is_read) as is_read'), 
            DB::raw('max(notifications.id) as max_id'), 
            // DB::raw('max(content) as contents'), 
            DB::raw('count(distinct(notifications.sender_id))  as sender_count'),
            DB::raw("case
                when count(DISTINCT(notifications.sender_id)) = 1 then users.firstname 
                when count(DISTINCT(notifications.sender_id)) >= 2 then  CONCAT(users.lastname, users.firstname,' 和另外', count(distinct(notifications.sender_id)) -1 , '名使用者' )
                end as sender_string"))
        ->join('users', 'users.id', '=', 'notifications.sender_id')
        ->whereRaw('user_id = ' . Auth::id())
        ->whereRaw('notifications.id = notifications.max_id')
        ->groupBy('notificatable_type', 'notificatable_id');
 
        $notifications = DB::table(DB::raw(sprintf('(%s) as ng', $notificationsGroup->toSql())))
            ->select('notifications.*', 'ng.sent_at', 'ng.is_read', 'ng.max_id',
                DB::raw("REPLACE(notifications.content, '{{ user }}', ng.sender_string) as content"))
            ->join('notifications', 'notifications.id', '=', 'ng.id')
            ->orderBy('ng.is_read', 'asc')
            ->orderBy('ng.sent_at', 'desc')
            ->limit(10);

             $users = DB::table('users');

// $users = DB::table('users')
//                      ->select(DB::raw('max(created_at) as user_count, gender'));
                     
                     




 		return $notificationsGroup->get();
        // if($this->unread)
        // {
        //     $notifications->where('ng.is_read', '=', 0);
        // }
 
        // return $this->toCollection($notifications->get());
    }

    public function sendFbmessenger($jsonData)
    {

        //API Url and Access Token, generate this token value on your Facebook App Page
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=EAAEPz1VMu8sBAJolmHCNTl5Ja2nYLZAhodvfxMim0WZAnB8sezkT58YJmxRjrITdig5jUjclSJJazbHKseKyfVwJ3tZAXNKDOsS9kyv1uonI1428FpvrWnsXDnThPpoioEguRT9EYMDa57eQxDg9RhegaYzKCu5nCe44S2L8AZDZD';
        //Initiate cURL.
        $ch = curl_init($url);
        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);

        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        //Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        //Execute the request but first check if the message is not empty.
        return curl_exec($ch);
    }






}
