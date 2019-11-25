<?php
namespace Repositories\Contracts;
use Auth;
interface NotificationRepositoryInterface extends BaseInterface {
	
	public function postToFacebookGroup($message,$token,$link,$group_id);
	public function sendEmail($view,$array,$user);
	public function postNotificationToUsers($users,$link,$content,$notificatable_id,$notificatable_type);
	public function postNotificationToUsersAPI($users,$link,$content,$notificatable_id,$notificatable_type);
	public function sendFbmessenger($jsonData);
}