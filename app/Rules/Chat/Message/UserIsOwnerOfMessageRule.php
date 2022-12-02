<?php

namespace App\Rules\Chat\Message;

use Illuminate\Contracts\Validation\Rule;
use App\MyStuff\Repos\ChatMessage\ChatMessageEloquentRepo;

class UserIsOwnerOfMessageRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($message_id)
    {
        $this->message_id = $message_id;
        $this->chatMessageRepo = resolve(ChatMessageEloquentRepo::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $message = $this->chatMessageRepo->find($this->message_id);

        if(!$message) return false;

        return auth()->user()->id == $message->user_id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('model.messageNotFound');
    }
}
