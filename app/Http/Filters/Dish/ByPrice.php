<?php

namespace App\Http\Filters\Dish;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByPrice
{
    public function __construct(protected Request $request)
    {
    }

    public function handle(Builder $builder, \Closure $next)
    {
        $builder = $next($builder);

        if ($this->request->has('min_price')) {
            $builder = $builder->where('price', '>=', $this->request->min_price);
        }

        if ($this->request->has('max_price')) {
            $builder = $builder->where('price', '<=', $this->request->max_price);
        }

        return $builder;
    }
}
