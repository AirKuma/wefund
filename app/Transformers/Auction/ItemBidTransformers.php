<?php
namespace App\Transformers\Auction;
use League\Fractal\TransformerAbstract;


class ItemBidTransformers extends TransformerAbstract
{
    public function transform($item_user)
    {
        // $order = 'desc';
        // if($item->type==1)
        //   $order = 'asc';

        return [
            'id' => $item_user->id,
            'firstname' => $item_user->firstname,
            'lastname' => $item_user->lastname,
            'gender' => $item_user->gender,
            'email' => $item_user->email,
            'phone' => $item_user->phone,
            'line_username' => $item_user->line_username,
            'telegram_username' => $item_user->telegram_username,
            'other_email' => $item_user->other_email,
            'price' => $item_user->pivot->price,
            'major' => $item_user->major->name,
            'facebook' => $item_user->facebook,
        ];
    }
}