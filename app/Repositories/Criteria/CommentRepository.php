<?php
namespace Repositories\Criteria;
use Repositories\Contracts\CommentRepositoryInterface;
use App\User;
use Auth;
class CommentRepository extends BaseRepository implements CommentRepositoryInterface {

	protected $modelName = 'App\Comment';




}