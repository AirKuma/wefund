<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function users(){

        return $this->belongsToMany('App\User', 'thread_participant', 'thread_id', 'user_id')
            ->withTimestamps();
    }
    
    public function messages()
    {
       return $this->hasMany('App\Message');
    }  

    public function userone()
    {
        return $this->belongsTo('App\User','user_one_id');
    }  

    public function usertwo()
    {
        return $this->belongsTo('App\User','user_two_id');
    } 
}
