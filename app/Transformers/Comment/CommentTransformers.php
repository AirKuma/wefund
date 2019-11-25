<?php
namespace App\Transformers\Comment;
use App\Comment;
use League\Fractal\TransformerAbstract;


class CommentTransformers extends TransformerAbstract
{
    public function transform(Comment $comment)
    {

        return [
            'id' => (int) $comment->id,
            'content' => $comment->content,
            'status' => $comment->status,
            'created_at' => $comment->created_at->diffForHumans(),
            'user' => $comment->user,
            'facebook' => $comment->user->facebook,
            //'item_userid' => $comment->item->user_id,
        ];
    }
}