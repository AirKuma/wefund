<?php
namespace Repositories\Criteria;
use Repositories\Contracts\ThreadRepositoryInterface;
use App\User;
use Auth;
class ThreadRepository extends BaseRepository implements ThreadRepositoryInterface {

	protected $modelName = 'App\Thread';




}