<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'type'];

    public function item()
    {
        return $this->hasMany('App\Item')->where('type','items');
    }
}
