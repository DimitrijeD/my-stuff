<?php

namespace App\Http\Requests\Chat\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatMessageRequest extends FormRequest
{
    const MESSAGE_TEXT_RULES = ['required', 'string', 'max:1000'];
    /**
     * If user belongs to chat group
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
            'text' => self::MESSAGE_TEXT_RULES,
            'user_id' => ['required', 'integer'],
            'group_id' => ['required', 'integer'],
        ];
    }
}
