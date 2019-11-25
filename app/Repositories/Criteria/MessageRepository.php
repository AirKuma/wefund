<?php
namespace Repositories\Criteria;
use Repositories\Contracts\MessageRepositoryInterface;
use App\User;
use Auth;
class MessageRepository extends BaseRepository implements MessageRepositoryInterface {

	protected $modelName = 'App\Message';




}