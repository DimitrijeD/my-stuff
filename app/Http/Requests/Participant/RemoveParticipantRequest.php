<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ChatRole;
use Illuminate\Validation\ValidationException;

class RemoveParticipantRequest extends FormRequest
{
    /**
     * @todo one of those requests where rules should be executed before authorize method, learn how to do that, then fix this
     */
    public function authorize()
    {
        $initiatorRole = $this->group->participants->where('id', $this->user->id)      ->first()?->pivot->participant_role;
        $targetRole    = $this->group->participants->where('id', $this->remove_user_id)->first()?->pivot->participant_role;

        if(!$initiatorRole)
            throw ValidationException::withMessages([ __('chat.noAccess') ]);

        if(!$targetRole)
            throw ValidationException::withMessages([ __('chat.participants.remove.failedMissingTargetRole') ]);
        
        return ChatRole::can([ $initiatorRole, $targetRole, $this->group->model_type ], ChatRole::ACTION_KEY_REMOVE);
    }

    public function rules()
    {
        return [
            'remove_user_id' => ['required', 'integer' ],
            'group_id' => ['required', 'integer', ],
        ];
    }
}
