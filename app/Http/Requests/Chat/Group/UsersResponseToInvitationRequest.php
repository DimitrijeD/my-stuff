<?php

namespace App\Http\Requests\Chat\Group;

use Illuminate\Foundation\Http\FormRequest;

class UsersResponseToInvitationRequest extends FormRequest
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
            'group_id' => ['required', 'integer'], 
            'responseToInvitation' => ['required', 'boolean'],
        ];
    }
}
