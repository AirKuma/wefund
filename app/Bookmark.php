<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
	protected $fillable = ['user_id','bookmarkable_id', 'bookmarkable_type'];

    public function bookmarkable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
