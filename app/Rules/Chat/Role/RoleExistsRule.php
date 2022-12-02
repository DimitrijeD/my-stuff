<?php

namespace App\Rules\Chat\Role;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Chat\ChatRole;

class RoleExistsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, ChatRole::ROLES);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __("Given ChatRole is not available.");
    }
}
