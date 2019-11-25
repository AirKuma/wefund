<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Item extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
	protected $fillable = ['name', 'price', 'description','new','free','category_id','target'];

    public function college()
    {
        return $this->belongsTo('App\User')->belongsTo('App\College');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function albums()
    {
        return $this->morphMany('App\Album', 'albumable');
    }

    public function users(){

        return $this->belongsToMany('App\User', 'item_user', 'item_id', 'user_id')
            ->withPivot('price','user_id')->withTimestamps();;
        // return $this->belongsToMany('App\User')
        //     ->withPivot('price', 'price')
        //     ->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function reports()
    {
        return $this->morphMany('App\Report', 'reportable');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function notifications()
    {
        return $this->morphMany('App\Notification', 'notificatable');
    }

    public function images()
    {
        return $this->hasManyThrough('App\Image', 'App\Album', 'albumable_id', 'album_id')->where('albumable_type','App\Item');
    }

    public function favors()
    {
        return $this->morphMany('App\Favor', 'favorable');
    }
}
