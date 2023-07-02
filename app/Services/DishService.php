<?php

namespace App\Services;

use Intervention\Image\ImageManagerStatic as Image;

class DishService
{
    public function saveImages($dish, $images): void
    {
        foreach ($images as $image) {
            $filename = uniqid('', true) . '.' . $image->getClientOriginalExtension();
            $location = storage_path('app/public/images/' . $filename);
            Image::make($image)->save($location);

            $dish->images()->create(['filename' => $filename]);
        }
    }
}
