<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Auth\PasswordReset;

class ResetPasswordRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
            'email' => ['required', 'email', 'string', ], 
            'token' => ['required', 'string', ] // 'digits:' . PasswordReset::EMAIL_HASH_LENGTH
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * These are cryptic reseponse messages which hide inner functinality of validation system from users/attackers.
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'token.required' => __('passwords.VE001'),
            // 'token.digits'   => __('passwords.VE002'),// cannot use this approach to exists because it exposes key 'token' and or 'email' from which attacker can get more info
            'email.required' => __('passwords.VE003'),
            'email.email'    => __('passwords.VE004'),
            'email.string'   => __('passwords.VE005'),
            // 'email.exists'   => __('passwords.VE006'), 
        ];
    }
}
