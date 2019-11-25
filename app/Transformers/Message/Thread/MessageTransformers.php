<?php
namespace App\Transformers\Message\Thread;
use App\Message;
use League\Fractal\TransformerAbstract;


class MessageTransformers extends TransformerAbstract
{
    public function transform(Message $message)
    {

        return [
            'id' => (int) $message->id,
            'body' => $message->body,
            'created_at' => $message->created_at->diffForHumans(),
        ];
    }
}