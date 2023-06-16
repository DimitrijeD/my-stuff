<?php

namespace App\Http\Requests\Chat\Message;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreChatMessageRequest extends FormRequest
{
    const MESSAGE_TEXT_RULES = ['sometimes', 'string', 'max:1000', 'nullable'];
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
            'group_id' => ['required', 'integer'],
            'files' => ['sometimes', 'array'],
        ];
    }
}
