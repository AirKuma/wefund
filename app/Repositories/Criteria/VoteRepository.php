<?php
namespace Repositories\Criteria;
use Repositories\Contracts\VoteRepositoryInterface;
use App\Vote;
use Auth;
class VoteRepository extends BaseRepository implements VoteRepositoryInterface {

	protected $modelName = 'App\Vote';




}