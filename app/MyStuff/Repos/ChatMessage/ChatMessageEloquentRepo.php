<?php

namespace App\MyStuff\Repos\ChatMessage;

use App\MyStuff\Repos\ChatMessage\Contracts\ChatMessageRepo;
use App\MyStuff\General\Traits\CRUDTrait;
use App\Models\ChatMessage;

class ChatMessageEloquentRepo implements ChatMessageRepo
{
    use CRUDTrait;

    public function getModel()
    {
        return ChatMessage::class;
    }

    public function getMissingMessages($group_id, $latest_msg_id)
    {
        return ChatMessage::
              where('group_id', $group_id)
            ->where('id', '>', $latest_msg_id)
            ->get();
    }

    public function getLatestMessages($group_id)
    {
        return ChatMessage::
              where('group_id', $group_id)
            ->orderBy('id', 'desc')
            ->take(ChatMessage::INIT_NUM_MESSAGES)
            ->get();
    }

    public function getBeforeMessage($group_id, $earliest_msg_id)
    {
        return ChatMessage::
              where('group_id', $group_id)
            ->where('id', '<', $earliest_msg_id)
            ->take(ChatMessage::EARLIEST_NUM_MESSAGES)
            ->orderBy('id', 'desc')
            ->get();
    }

}
