<?php
namespace App\Transformers\Image;
use App\Image;
use League\Fractal\TransformerAbstract;

use Cloudder;
class ImageTransformers extends TransformerAbstract
{
	
    public function transform(Image $images)
    {
         
       $surl = Cloudder::secureShow($images->file_name, ["width"=>203, "crop"=>"scale"]);
       $murl = Cloudder::secureShow($images->file_name, ["width"=>300, "height"=>400, "crop"=>"limit"]);
       $weburl = Cloudder::secureShow($images->file_name, ["width"=>500, "height"=>500, "crop"=>"limit"]);
        

        return [
            'id' => (int) $images->id,
            'surl' => $surl,
            'murl' => $murl,
            'weburl' => $weburl,
            'file_name' => $images->file_name,
            'file_path' => $images->file_path
        ];
    }
}