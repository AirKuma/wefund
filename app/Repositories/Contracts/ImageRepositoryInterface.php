<?php
namespace Repositories\Contracts;
use Auth;
interface ImageRepositoryInterface extends BaseInterface {
	
	public function uploadImage(array $image , $user_id, $path, $weight = 600, $height = 400);
	public function deleteImageFile(array $image , $path);

}