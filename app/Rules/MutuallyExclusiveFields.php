<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MutuallyExclusiveFields implements ValidationRule
{
    private string $otherField;

    public function __construct(string $otherField)
    {
        $this->otherField = $otherField;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        if (request()->has($this->otherField)) {
            $fail('The :attribute and ' . $this->otherField . ' cannot both be present.');
        }
    }
}
