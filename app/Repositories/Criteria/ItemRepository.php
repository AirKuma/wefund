<?php
namespace Repositories\Criteria;
use Repositories\Contracts\ItemRepositoryInterface;
use App\User;
use Auth;
class ItemRepository extends BaseRepository implements ItemRepositoryInterface {

	protected $modelName = 'App\Item';




}