<?php
namespace Repositories\Criteria;
use Repositories\Contracts\BillboardRepositoryInterface;
use App\User;
use Auth;
class BillboardRepository extends BaseRepository implements BillboardRepositoryInterface {

	protected $modelName = 'App\Billboard';




}