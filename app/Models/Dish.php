<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
