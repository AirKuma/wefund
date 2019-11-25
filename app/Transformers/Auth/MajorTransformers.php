<?php
namespace App\Transformers\Auth;
use App\Major;
use League\Fractal\TransformerAbstract;


class MajorTransformers extends TransformerAbstract
{
    public function transform(Major $major)
    {
        return [
            'id' => (int) $major->id,
            'name' => $major->name,
        ];
    }
}