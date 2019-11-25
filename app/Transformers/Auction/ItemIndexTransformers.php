<?php
namespace App\Transformers\Auction;
use App\Item;
use League\Fractal\TransformerAbstract;

use Cloudder;
class ItemIndexTransformers extends TransformerAbstract
{
    //public $fileUrl;
    public function transform(Item $item)
    {

		//項目價錢      
      	if($item->users->first()!=null){
      		if($item->type==0)
      			$price = $item->users()->orderBy('item_user.price', 'desc')->first()->pivot->price;
      		else
      			$price = $item->users()->orderBy('item_user.price', 'asc')->first()->pivot->price;
      	}
      	elseif($item->free==1)
      		$price = null;
      	else
      		$price = $item->price;

        $fileUrl = null;
        if($item->albums->first()->images->first()){
          $fileUrl = Cloudder::show($item->albums->first()->images->first()->file_name, ["width"=>203, "crop"=>"scale"]);
        }

        return [
            'id' => (int) $item->id,
            'description' => $item->description,
            'name' => $item->name,
            'price' => $price,
            'baseprice' => $item->price,
            'new' => $item->new,
            'free' => $item->free,
            'end_time' => $item->end_time,
            'start_time' => $item->start_time,
            'type' => $item->type,
            'repost' => $item->repost,
            'disabled' => $item->disabled,
            'start_time' => $item->start_time,
            'bid_count' => $item->users()->count(),
            'category' => $item->category,
            //'image' => $item->albums->first()->images->first(),
            'nailthumb' => $fileUrl, 
            'favor_count' => $item->favors()->count(),
        ];
    }
}