<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Carbon\Carbon;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];



    public function accountIsActive($code) {

        $user = User::where('activation_code', '=', $code)->first();
        if($user){
            $user->actived = 1;
            $user->activated_at = Carbon::now();
            $user->activation_code = '';
            if($user->save()) {
                \Auth::login($user);
            }
            return true;
        }else{
            return false;
        }

    }

    public function accountIsActiveAPI($code) {

        $user = User::where('activation_code', '=', $code)->first();
        if($user){
            $user->actived = 1;
            $user->activated_at = Carbon::now();
            $user->activation_code = '';
            $user->save();
            // if($user->save()) {
            //     \Auth::login($user);
            // }
            return true;
        }else{
            return false;
        }

    }

    public function facebook()
    {
        return $this->hasOne('App\Facebook');
    }

    public function albums()
    {
        return $this->morphMany('App\Album', 'albumable');
    }

    public function college()
    {
        return $this->belongsTo('App\College');
    }

    public function major()
    {
        return $this->belongsTo('App\Major');
    }

    public function item()
    {
       return $this->hasMany('App\Item');
    }

    public function items()
    {
        return $this->belongsToMany('App\Item', 'item_user', 'user_id', 'item_id')
            ->withPivot('price')->withTimestamps();
    }

    public function messages()
    {
       return $this->hasMany('App\Message');
    }    

    public function threads()
    {
        return $this->belongsToMany('App\Thread', 'thread_participant', 'user_id', 'thread_id')
            ->withTimestamps();
    }

    /*public function subscriptions()
    {
        return $this->morphMany('App\Subscription', 'subscriptable');
    }*/

    public function subscriptions()
    {
       return $this->hasMany('App\Subscription')->where('subscriptable_type','App\Billboard');
    }  

    /*public function billboard()
    {
        return $this->hasManyThrough('App\Billboard', 'App\Subscription','user_id','subscriptable_id');
    }*/

    public function billboards()
    {
        return $this->belongsToMany('App\Billboard', 'subscriptions', 'user_id', 'subscriptable_id')->where('subscriptable_type','App\Billboard')
            ->withTimestamps();
    }

    public function post()
    {
       return $this->hasMany('App\Post');
    }

    public function billboard()
    {
       return $this->hasMany('App\Billboard');
    }

    public function votes()
    {
       return $this->hasMany('App\Vote');
    }

    public function bookmarks()
    {
       return $this->hasMany('App\Bookmark');
    }

    public function user_bookmarks()
    {
        return $this->belongsToMany('App\Post', 'bookmarks', 'user_id', 'bookmarkable_id')
            ->withTimestamps();
    }

    public function admin_billboards()
    {
        return $this->belongsToMany('App\Billboard', 'admins', 'user_id', 'adminable_id')->where('adminable_type','App\Billboard')
            ->withTimestamps();
    }

    public function admins()
    {
       return $this->hasMany('App\Admin')->where('adminable_type','App\Billboard');
    }

    public function notifications()
    {
       return $this->hasMany('App\Notification');
    }

}
