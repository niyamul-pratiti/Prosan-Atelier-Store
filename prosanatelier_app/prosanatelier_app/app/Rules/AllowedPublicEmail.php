<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedPublicEmail implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || trim((string) $value) === '') {
            return;
        }

        $email = strtolower(trim((string) $value));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $fail('Please enter a valid email address.');
            return;
        }

        $domain = substr(strrchr($email, '@') ?: '', 1);

        if ($domain === '' || ! str_ends_with($domain, '.com')) {
            $fail('Only Gmail or other .com email addresses are accepted.');
        }
    }
}
