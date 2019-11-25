<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{

	protected $table = 'albums';
	protected $fillable = ['user_id', 'name', 'albumable_id', 'albumable_type'];

    public function albumtable()
    {
        return $this->morphTo();
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /*public function item()
    {
        return $this->belongsTo('App\Item');
    }*/

    public function item2()
    {
        return $this->belongsTo('App\Item','albumable_id');
    }
}
