<?php
namespace Repositories\Criteria;
use Repositories\Contracts\BidRepositoryInterface;
use App\User;
use Auth;
class BidRepository extends BaseRepository implements BidRepositoryInterface {

	protected $modelName = 'App\Bid';




}