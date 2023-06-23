<?php

namespace App\Rules;

use App\Models\Table;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TableIsNotReserved implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $table = Table::find($value);
        if ($table && $table->status === 'reserved') {
            $fail('The selected table is reserved.');
        }
    }
}
