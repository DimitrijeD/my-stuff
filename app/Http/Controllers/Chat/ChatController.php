<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Cache\ChatRoleRulesCache;

use App\Models\Chat\ChatRole;
use App\Models\Chat\ChatGroup;
use App\Models\Chat\ChatMessage;

class ChatController extends Controller
{
    /**
     * Provides all initial data required for chat,
     * 
     * If not stored in Redis, it will generate rules (createTableArrayOfRules()) on each request.
     */
    public function init(ChatRoleRulesCache $chatRoleRules)
    {
        $roleTables = $chatRoleRules->getAll();

        return response()->json([
            'chat_rules' => [
                'chat_rules' => $roleTables 
                    ? $roleTables
                    : $chatRoleRules->createTableArrayOfRules(), 
                    
                'keys' => ChatRole::ACTION_KEYS,
                'roles' => ChatRole::ROLES,
                'groupTypes' => ChatGroup::TYPES,
                'default_type' => ChatGroup::TYPE_DEFAULT,

                'init_num_messages' => ChatMessage::INIT_NUM_MESSAGES,
                'earliest_num_messages' => ChatMessage::EARLIEST_NUM_MESSAGES,
            ],
            
            'groups' => auth()->user()->groups,
        ]);
    }
}
