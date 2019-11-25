<?php
namespace Repositories\Criteria;
use Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Subscription;
use Auth;
class SubscriptionRepository extends BaseRepository implements SubscriptionRepositoryInterface {

	protected $modelName = 'App\Subscription';




}