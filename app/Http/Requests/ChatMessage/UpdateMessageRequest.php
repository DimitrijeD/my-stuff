<?php

namespace App\Http\Requests\ChatMessage;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ChatMessage\UserIsOwnerOfMessageRule;

class UpdateMessageRequest extends FormRequest
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
            'message_id' => ['required', new UserIsOwnerOfMessageRule($this->message_id)],
            'text' => ['required', 'string', StoreChatMessageRequest::MESSAGE_TEXT_RULES]
        ];
    }
}
