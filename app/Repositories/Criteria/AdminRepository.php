<?php
namespace Repositories\Criteria;
use Repositories\Contracts\AdminRepositoryInterface;
use App\User;
use Auth;
class AdminRepository extends BaseRepository implements AdminRepositoryInterface {

	protected $modelName = 'App\Admin';




}