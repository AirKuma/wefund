<?php
namespace Repositories\Contracts;
use Auth;
interface ClubRepositoryInterface extends BaseInterface {
	
	public function permission($club_id, $user_id, $permission = 2);
	public function request($club_id, $user_id);
	
}