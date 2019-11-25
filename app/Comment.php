<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Comment extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
	protected $fillable = ['user_id', 'content', 'commentable_id', 'commentable_type','anonymous'];

    public function commentable()
    {
        return $this->morphTo();
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function votes()
    {
        return $this->morphMany('App\Vote', 'votable');
    }

    public function reports()
    {
        return $this->morphMany('App\Report', 'reportable');
    }

    public function item()
    {
        return $this->belongsTo('App\Item','commentable_id');
    }
}
