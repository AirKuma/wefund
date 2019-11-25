<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
	protected $fillable = ['user_id', 'content', 'reportable_id', 'reportable_type'];

    public function reportable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
}
