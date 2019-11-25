<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billboard extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'description','type','anonymous','adult','domain','target','limit_college'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function subscriptions()
    {
        return $this->morphMany('App\Subscription', 'subscriptable');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function admins()
    {
        return $this->morphMany('App\Admin', 'adminable');
    }

    public function college()
    {
        return $this->belongsTo('App\College');
    }

    public function categories()
    {
       return $this->hasMany('App\BillboardCategory');
    }

    public function subscribers()
    {
        return $this->belongsToMany('App\User', 'subscriptions', 'subscriptable_id', 'user_id')->where('subscriptable_type','App\Billboard')
            ->withTimestamps();
    }
}
