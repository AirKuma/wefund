<?php
namespace App\Transformers\Auth;
use App\College;
use League\Fractal\TransformerAbstract;


class CollegeTransformers extends TransformerAbstract
{
    public function transform(College $college)
    {
        return [
            'id' => (int) $college->id,
            'name' => $college->name,
            'acronym' => $college->acronym,
        ];
    }
}