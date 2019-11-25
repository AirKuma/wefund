<?php
namespace Repositories\Criteria;
use Repositories\Contracts\AlbumRepositoryInterface;
use App\User;
use Auth;
class AlbumRepository extends BaseRepository implements AlbumRepositoryInterface {

	protected $modelName = 'App\Album';




}