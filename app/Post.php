<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $fillable = ['billboard_id', 'title', 'link','content','anonymous'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function billboard()
    {
        return $this->belongsTo('App\Billboard');
    }

    public function votes()
    {
        return $this->morphMany('App\Vote', 'votable');
    }

    public function bookmarks()
    {
        return $this->morphMany('App\Bookmark', 'bookmarkable');
    }

    public function reports()
    {
        return $this->morphMany('App\Report', 'reportable');
    }

    public function billboards()
    {

        return $this->belongsToMany('App\BillboardCategory', 'billboard_category_post', 'post_id', 'billboardcategory_id')
            ->withTimestamps();

    }
}
