<?php
namespace App\Transformers\Message\Thread;
use App\User;
use League\Fractal\TransformerAbstract;


class UserTransformers extends TransformerAbstract
{
    
    public function transform(User $user)
    {

        return [
            'id' => (int) $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'gender' => $user->gender,
            'facebook' => $user->facebook,
        ];
    }

}