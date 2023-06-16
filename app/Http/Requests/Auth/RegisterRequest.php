<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    const MAX_IMAGE_SIZE = 5120;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'     => self::getUserNameRules(),
            'last_name'      => self::getUserNameRules(),
            'email'          => ['required', 'string', 'min:3', 'max:255', 'email', 'unique:users'],
            'password'       => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
            'profilePicture' => ['sometimes', 'file', 'image', 'max:'.self::MAX_IMAGE_SIZE],
        ];
    }

    public static function getUserNameRules()
    {
        return [
            'required', 'string', 'min:3', 'max:255',
        ];
    }
}
