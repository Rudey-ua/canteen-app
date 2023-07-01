<?php

namespace App\Http\Filters\Dish;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByCategory
{
    public function __construct(protected Request $request)
    {
    }

    public function handle(Builder $builder, \Closure $next)
    {
        return $next($builder)
            ->when($this->request->has('category_id'),
                fn($query) => $query->where('category_id', $this->request->category_id)
            );
    }
}
