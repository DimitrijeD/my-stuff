<?php

return [

    'participants' => [
        'add' => [
            'success' => 'User added.|Users added.',
            'failOnCreate' => 'An error occured while trying to add users. Please try again.',
            'alreadyPresent' => "Some or all selected users are already present. Please select only those which are not in chat.",
            'notAuthorizedToAddUsers' => 'You cannot add users to chat.',
        ],

        'leave' => [
            'success' => 'Group left.'
        ],

        'remove' => [
            'failedMissingTargetRole' => 'An error occured. Cannot remove this participant from chat.',
            'success' => 'User has been removed from chat.',
            'youRemoved' => 'You have been removed from chat :name .',
        ],

        'role' => [
            'change' => "User's role has been changed.",
            'forTargetChange' => "Your role in this chat has been changed.",
            'gone' => 'User you are trying to change role to is not in chat. First add him.',
        ]
    ],

    'noAccess' => 'You have no access to this chat.',

    'name' => [
        'updated' => 'Chat name updated.',
        'failedUpdating' => 'Failed changing chat name.',
    ]

];
