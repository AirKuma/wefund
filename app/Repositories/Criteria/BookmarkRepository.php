<?php
namespace Repositories\Criteria;
use Repositories\Contracts\BookmarkRepositoryInterface;
use App\User;
use Auth;
class BookmarkRepository extends BaseRepository implements BookmarkRepositoryInterface {

	protected $modelName = 'App\Bookmark';




}