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
            'gone' => 'User you are trying to change role to user which is not in chat.',
        ]
    ],

    'noAccess' => 'You have no access to this chat.',

    'name' => [
        'updated' => 'Chat name updated.',
        'failedUpdating' => 'Failed changing chat name.',
    ],
    
    'storeGroup' => [
        'minNumUsers' => 'Chat must contain at least 2 users.',
        'invalidGroupType' => 'Group type is not available.',
        'someUsersDontExist' => "Cannot add participants which don't exist."
    ],
    
    'type' => [
        'success' => 'Chat type has been changed. Users in group now have different authorizations according to their role.',
        'failedUpdating' => 'An error occured while trying to update group type.',
    ],

    'message' => [
        'notOwner' => 'This is not your message. You cannot delete it.',
        'deleted' => 'Message has been deleted.',
        'updated' => 'Message has been updated.'
    ]
];
