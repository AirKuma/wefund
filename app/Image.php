<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $table = 'images';
	protected $fillable = ['file_name','file_size','file_mime','file_path', 'description'];

	public function album()
	{
		return $this->belongsTo('App\Album');
	}

}
