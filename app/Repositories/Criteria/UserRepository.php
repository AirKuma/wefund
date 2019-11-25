<?php
namespace Repositories\Criteria;
use Repositories\Contracts\UserRepositoryInterface;
use App\User;
use Auth;
class UserRepository extends BaseRepository implements UserRepositoryInterface {

	protected $modelName = 'App\User';




}