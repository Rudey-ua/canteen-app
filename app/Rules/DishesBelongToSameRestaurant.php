<?php

namespace App\Rules;

use App\Models\Dish;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DishesBelongToSameRestaurant implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $restaurantId = null;

        foreach ($value as $dishId) {
            $dish = Dish::find($dishId['id']);

            if (!$dish) {
                $fail("Dish with id {$dishId['id']} does not exist.");
                return;
            }

            if ($restaurantId === null) {
                $restaurantId = $dish->restaurant_id;
            } elseif ($restaurantId !== $dish->restaurant_id) {
                $fail('All dishes must belong to the same restaurant.');
                return;
            }
        }
    }
}
