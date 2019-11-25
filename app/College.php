<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $colleges = 'colleges';

	// public function scopeFindCollegeByEmail($query, $email)
	// {
	//         return $query->where('email', 'LIKE', '%fju%');
	// }


    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function items()
    {
        return $this->hasManyThrough('App\Item', 'App\User');
    }

    public function billboard()
    {
       return $this->hasMany('App\Billboard');
    }
}
