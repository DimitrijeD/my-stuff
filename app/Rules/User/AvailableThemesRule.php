<?php

namespace App\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use App\Models\UserSettings;

class AvailableThemesRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($theme)
    {
        $this->theme = $theme;
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
        return in_array($this->theme, UserSettings::THEMES);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('user.setting.theme.notAvailable', ['theme' => $this->theme]);
    }
}
