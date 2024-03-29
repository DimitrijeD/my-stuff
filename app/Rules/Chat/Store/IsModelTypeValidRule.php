<?php

namespace App\Rules\Chat\Store;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Chat\ChatGroup;

class IsModelTypeValidRule implements Rule
{
    public function __construct($model_type)
    {
        $this->model_type = $model_type;
    }

    public function passes($attribute, $value)
    {
        return in_array($this->model_type, ChatGroup::TYPES);
    }

    public function message()
    {
        return __("chat.storeGroup.invalidGroupType");
    }
}
