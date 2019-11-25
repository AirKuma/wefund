<?php
namespace App\Transformers\Notification;
use App\Notification;
use League\Fractal\TransformerAbstract;


class NotificationTransformers extends TransformerAbstract
{
    public function transform(Notification $notification)
    {

        return [
            'id' => (int) $notification->id,
            'notificatable_id' => $notification->notificatable_id,
            'content' => $notification->content,
            'link' => $notification->link,
            'created_at' => $notification->created_at->diffForHumans(),
            'is_read' => $notification->is_read,
            'sender' => $notification->sender,
        ];
    }
}