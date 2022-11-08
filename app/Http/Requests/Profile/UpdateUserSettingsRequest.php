<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\User\AvailableThemesRule;
use App\Http\Requests\Auth\RegisterRequest;

class UpdateUserSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'userFields.first_name' => array_merge(['sometimes'], RegisterRequest::getUserNameRules()),
            'userFields.last_name' => array_merge(['sometimes'], RegisterRequest::getUserNameRules()),

            'settingsFiels.open_all_chats_on_new_message' => ['sometimes', 'boolean'],
            'settingsFiels.show_only_important_notifications' => ['sometimes', 'boolean'],
            'settingsFiels.theme' => ['sometimes', 'string', $this->themeRule() ? new AvailableThemesRule($this->settingsFiels['theme']) : '' ], // @todo, if colorz implementation is success, remove this field
            'settingsFiels.colorz' => ['sometimes', 'array'], // @todo implement complete validation for this nested array. Idea: somehere in php, store structure and provide it to FE 
        ];
    }

    private function themeRule()
    {
        if(!$this->settingsFiels) return false;

        return array_key_exists('theme', $this->settingsFiels);
    }

}
