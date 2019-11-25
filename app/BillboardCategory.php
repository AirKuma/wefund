<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillboardCategory extends Model
{
    protected $fillable = ['name'];

    public function posts()
    {

        return $this->belongsToMany('App\Post', 'billboard_category_post', 'billboardcategory_id','post_id')
            ->withTimestamps();

    }

    public function billboard()
    {
        return $this->belongsTo('App\Billboard');
    }
}
