<?php
namespace Repositories\Criteria;
use Repositories\Contracts\PostRepositoryInterface;
use App\Post;
use Auth;
class PostRepository extends BaseRepository implements PostRepositoryInterface {

	protected $modelName = 'App\Post';




}