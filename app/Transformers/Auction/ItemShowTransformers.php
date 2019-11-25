<?php
namespace App\Transformers\Auction;
use App\Item;
use League\Fractal\TransformerAbstract;
use App\Transformers\Image\ImageTransformers;

class ItemShowTransformers extends TransformerAbstract
{   
    // protected $defaultIncludes = [
    //     'images'
    // ];

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
      		$price = "";
      	else
      		$price = $item->price;


        return [
            'id' => (int) $item->id,     
            'description' => $item->description,
            'name' => $item->name,
            'price' => $price,
            'new' => $item->new,
            'free' => $item->free,
            'end_time' => $item->end_time,
            'start_time' => $item->start_time,
            'type' => $item->type,
            'target' => $item->target,
            'repost' => $item->repost,
            'start_time' => $item->start_time,
            'disabled' => $item->disabled,
            'user_id' => $item->user_id,
            'bid_count' => $item->users()->count(),
            'category' => $item->category,
            //'image' => $item->albums->first()->images,
            'comment_count' => $item->comments()->count(),
            'favor_count' => $item->favors()->count(),
        ];
    }

    // public function includeImages(Item $item)
    // {
    //     $image = $item->images;
    //     return $image;
    //     if($image)
    //       return $this->collection($image, new ImageTransformers());
    // }
}