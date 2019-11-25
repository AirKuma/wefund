<?php
namespace Repositories\Criteria;
use Repositories\Contracts\ProfileRepositoryInterface;
use App\User;
use Auth;
class ProfileRepository extends BaseRepository implements ProfileRepositoryInterface {

	protected $modelName = 'App\User';




}