<?php
namespace App\Transformers\Category;
use App\Category;
use League\Fractal\TransformerAbstract;


class CategoryTransformers extends TransformerAbstract
{
    public function transform(Category $categories)
    {

        return [
            'id' => (int) $categories->id,
            'name' => $categories->name,
            'en_name' => $categories->en_name,
        ];
    }
}