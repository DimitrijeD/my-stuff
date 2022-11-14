<?php

namespace App\Http\Requests\ChatGroup;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ChatRole;

class ChangeGroupTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ChatRole::can([ 
            $this->group->participants->where('id', $this->user->id)->first()?->pivot->participant_role,
            $this->model_type 
        ], ChatRole::ACTION_KEY_CHANGE_GROUP_TYPE);
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
            'model_type' => ['required', 'string', ]
        ];
    }
}
