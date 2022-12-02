<?php

namespace App\Http\Requests\Chat\Message;

use Illuminate\Foundation\Http\FormRequest;

class GetMissingMessagesRequest extends FormRequest
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
            'latest_msg_id' => ['required', 'integer',],
            'group_id' => ['required', 'integer'],
        ];
    }
}
