<?php
namespace App\Transformers\Auth;
use App\User;
use League\Fractal\TransformerAbstract;


class UserTransformers extends TransformerAbstract
{
    protected $defaultIncludes = [
        'college'
    ];
        
    public function transform(User $user)
    {

        return [
            'id' => (int) $user->id,
            'username' => $user->username,
            'other_email' => $user->other_email,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'birthday' => $user->birthday,
            'gender' => $user->gender,
            'phone' => $user->phone,
            'line_username' => $user->line_username,
            'telegram_username' => $user->telegram_username,
            'actived' => $user->actived,
            'major_id' => $user->major_id,
            'college_id' => $user->college_id,
            'user_item' => $user->item->count(),
            'user_bid' => $user->items->groupBy('id')->count(),
            //'college' => $user->college->name,
            'facebook' => $user->facebook,
        ];
    }

    public function includeCollege(User $user)
    {
        $college = $user->college->get();
        
        return $this->collection($college, new CollegeTransformers());
    }
}