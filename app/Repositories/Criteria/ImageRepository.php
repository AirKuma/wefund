<?php
namespace Repositories\Criteria;
use Repositories\Contracts\ImageRepositoryInterface;
use App\User;
use App\Album;
use App\Club;
use Auth;
use Intervention\Image\Facades\Image;
class ImageRepository extends BaseRepository implements ImageRepositoryInterface {

	protected $modelName = 'App\Image';

	//public function uploadImage(array $image ,$album_id, $user_id)
        public function uploadImage(array $image , $user_id, $path, $weight = 600, $height = 400)
	{
		//$instance = $this->getNewInstance();
		//Upload File


        if(\Input::hasFile('image')) {

        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }
        if(!file_exists($path . '/thumbs')){
            mkdir($path . '/thumbs', 0777, true);
        }
        $images = \Input::file('image');

        //$file_count = count($images);
        //$uploadcount = 0;
        $image_array = array();

        foreach($images as $image) {

        $tmpFileName = time() . '-' . $image->getClientOriginalName();
        $tmpFileName = str_replace(' ', '', $tmpFileName);
	$tmpFileName = str_replace('.', '', $tmpFileName);

        $thumb = Image::make($image->getRealPath())->resize(240, 160, function($constraint)
        {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(public_path() . '/' . $path . '/thumbs/' . $tmpFileName.'.jpg');

        $image = Image::make($image->getRealPath())->resize($weight, $height, function($constraint)
        {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(public_path() . '/' . $path . '/' . $tmpFileName.'.jpg');

        $image_array[] = $image;

        }

        //Add File record to DB
        //$album = Album::find($album_id);
        //$album->images()->create(['image' => $path, 'description' => $image['description'] ]);

        //$instance->album()->create(['image' => '$path', 'description' => '$image->description']);
        //$image->save();

        //return $album->images()->create(['image' => $path, 'description' => $image['description'] ]); //***
        //return $tmpFilePath . $tmpFileName;
        //return '/' . $path . '/' . $tmpFileName;
        return $image_array;
        }
        
        // return $album->images()->create(['image' => $path, 'description' => $image['description'] ]);
        //return dd(\Input::hasFile('image'));
		//$instance = $instance->find($album_id)->images()->create($img);
	   }

        public function deleteImageFile(array $file, $path){
            //return dd($file['file_path']);
            $image = public_path() . $file['file_path'];
            $thumbsimg = public_path() .'/'. $path .'/thumbs/'. $file['file_name'];
            //return dd(\File::isFile($thumbsimg));
            if(\File::isFile($image) && \File::isFile($thumbsimg)){
                \File::delete($image);
                \File::delete($thumbsimg);
            }
            //return dd($file);
        }

}
