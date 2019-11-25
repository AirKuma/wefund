<?php
namespace Repositories\Criteria;
use Repositories\Contracts\ClubRepositoryInterface;
use App\User;
use Auth;
class ClubRepository extends BaseRepository implements ClubRepositoryInterface {

	protected $modelName = 'App\Club';

	public function permission($club_id, $user_id , $permission = 2)
	{
		$instance = $this->getNewInstance();
		$instance = $instance->find($club_id)->users()->wherePivot('user_id', '=', $user_id)->first();
		$instance->pivot->permission = $permission;
		
		return $instance->pivot->save();
	}

	public function request($club_id, $user_id)
	{
		$instance = $this->getNewInstance();
		return $instance = $instance->find($club_id)->users()->sync(['user_id' => $user_id], false);
	}


}