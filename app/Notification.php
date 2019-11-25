<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	protected $fillable = ['user_id', 'sender_id', 'content' , 'notificatable_id', 'notificatable_type'];

    public function notificatable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function item()
    {
        return $this->belongsTo('App\Item','notificatable_id');
    }
    public function post()
    {
        return $this->belongsTo('App\Post','notificatable_id');
    }
	// public function scopeLink($query)
	// {
		
	//     return $query->where('notificatable_id', '=', 0);
	// }
    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }
    
}
