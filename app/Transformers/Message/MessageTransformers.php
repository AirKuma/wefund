<?php
namespace App\Transformers\Message;
use App\Message;
use League\Fractal\TransformerAbstract;
use App\Transformers\Message\Thread\UserTransformers;


class MessageTransformers extends TransformerAbstract
{

	protected $defaultIncludes = [
        'user',
    ];

    public function transform(Message $message)
    {

        return [
            'id' => (int) $message->id,
            'body' => $message->body,
            'created_at' => $message->created_at->diffForHumans(),
            'thread_id' => $message->thread_id,
            //'user' => $message->user,
        ];
    }

    public function includeUser(Message $message)
    {
        $user = $message->user()->first();
        $userdata = $user->where('id',$user->id)->get();
        
        return $this->collection($userdata, new UserTransformers());
    }
}