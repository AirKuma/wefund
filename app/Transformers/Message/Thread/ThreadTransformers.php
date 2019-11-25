<?php
namespace App\Transformers\Message\Thread;
use App\Thread;
use League\Fractal\TransformerAbstract;



class ThreadTransformers extends TransformerAbstract
{
	protected $defaultIncludes = [
        'messages',
        'userone',
        'usertwo'
    ];


    public function transform(Thread $threads)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();

        return [
            'id' => (int) $threads->id,
            'read_count' => $threads->messages()->whereNotIn('user_id', [$user->id])->where('status', 0)->count(),
            //'created_at' => $threads->messages->orderby('created_at','desc')->first()->created_at->diffForHumans(),
            //'userone_facebook' => $threads->userone->facebook,
            //'usertwo_facebook' => $threads->usertwo->facebook,
        ];
    }

    public function includeMessages(Thread $threads)
    {
        $messages = $threads->messages()->orderby('created_at','desc')->first();
        $message = $messages->where('id',$messages->id)->get();
        
        return $this->collection($message, new MessageTransformers());
    }

    public function includeUserone(Thread $threads)
    {
        $user = $threads->userone()->first();
        $userone = $user->where('id',$user->id)->get();
        
        return $this->collection($userone, new UserTransformers());
    }

    public function includeUsertwo(Thread $threads)
    {
        $user = $threads->usertwo()->first();
        $usertwo = $user->where('id',$user->id)->get();
        
        return $this->collection($usertwo, new UserTransformers());
    }
}