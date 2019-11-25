<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
	protected $fillable = ['user_id', 'allow', 'subscriptable_id', 'subscriptable_type'];

    public function subscriptable()
    {
        return $this->morphTo();
    }

    public function billboards()
    {
       return $this->belongsTo('App\Billboard','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
