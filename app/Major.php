<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
	protected $colleges = 'majors';

    public function users()
    {
        return $this->hasMany('App\User');
    }

}
