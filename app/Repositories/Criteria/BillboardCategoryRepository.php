<?php
namespace Repositories\Criteria;
use Repositories\Contracts\BillboardCategoryRepositoryInterface;
use App\BillboardCategory;
use Auth;
class BillboardCategoryRepository extends BaseRepository implements BillboardCategoryRepositoryInterface {

	protected $modelName = 'App\BillboardCategory';




}