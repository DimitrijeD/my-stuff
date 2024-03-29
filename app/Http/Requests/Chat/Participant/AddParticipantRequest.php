<?php

namespace App\Http\Requests\Chat\Participant;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Chat\Store\ParticipantsExistRule;
use App\Exceptions\UnAuthrorizedException;
use App\Models\Chat\ChatRole;

class AddParticipantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to add all of these users in this group on determined role.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!$requestInitiator = $this->group->participants->find($this->user->id))
            throw new UnAuthrorizedException(__('chat.participants.add.notAuthorizedToAddUsers'));
       
        foreach($this->usersToAdd as $userToAdd){
            if(!ChatRole::can([
                    $requestInitiator->pivot->participant_role,
                    $userToAdd['target_role'], 
                    $this->group->model_type,
                ],
                ChatRole::ACTION_KEY_ADD
            )) throw new UnAuthrorizedException(__('chat.participants.add.notAuthorizedToAddUsers'));
        }

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
            'group_id' => ['required', 'integer'],
            'usersToAdd' => ['required', 'array',  new ParticipantsExistRule($this->formatUserIds($this->usersToAdd))],
        ];
    }

    private function formatUserIds($usersToAdd)
    {
        $arrayOfUserIds = [];
        foreach($usersToAdd as $user){
            $arrayOfUserIds[] = $user['user_id'];
        }
        return $arrayOfUserIds;
    }
}
