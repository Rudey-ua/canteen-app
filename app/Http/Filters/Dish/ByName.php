<?php

namespace App\Http\Filters\Dish;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class ByName
{
    public function __construct(protected Request $request)
    {
    }

    public function handle(Builder $builder, \Closure $next)
    {
        return $next($builder)
            ->when($this->request->has('name'),
                fn($query) => $query->where('name', 'LIKE', '%' . $this->request->name . '%')
            );
    }
}
