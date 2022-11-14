<?php

namespace App\Http\Requests\ChatGroup;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ChatGroup\Store\IsModelTypeValidRule;
use App\Rules\ChatGroup\Store\ParticipantsExistRule;
use App\Rules\ChatGroup\Store\MoreThanOneParticipantRule;

class StoreGroupRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['string', 'max:255', 'nullable'],
            'model_type' => ['string', 'max:255', 'nullable', new IsModelTypeValidRule($this->model_type)],
            'users_ids' => [new MoreThanOneParticipantRule($this->users_ids), new ParticipantsExistRule($this->users_ids), ],
        ];
    }
    
}
