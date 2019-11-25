<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favor extends Model
{
    protected $fillable = ['user_id', 'favorable_id', 'favorable_type'];

    public function favorable()
    {
        return $this->morphTo();
    }
}
