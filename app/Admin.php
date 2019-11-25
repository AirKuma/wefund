<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['user_id','adminable_id', 'adminable_type'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
