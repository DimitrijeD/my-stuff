export default () => {
    return {
        id: null,
        messages: {},
        model_type: null,
        name: null,
        participants: {},
        created_at: null,
        updated_at: null,
        messages_tracker: {
            gotEarliestMsg: false,
            last_message: {},
            seen: null,
        },
        whoSawWhat: {},
        window: {
            minimized: false,
            showConfig: false,
            hasInitMessages: false,
            scrolledDownInitialy: false,
        },
        typing: {
            user_ids: [],
            timeouts: {},
            showTyperFor: 3500,
        },
        permissions: {
            add: [],
            change_group_name: null,
            change_group_type: null,
            change_role: {},
            remove: [],
            send_message: null,
        }
    }
}

/**
 * @id -int
 * 
 * @messages - object/dictionary where key is 'id' of 'message' model and value is 'message'.  Message model contains:
 *      > @id
 *      > @text
 *      > @user_id
 *      > @created_at
 *      > @updated_at
 * 
 * @model_type - string
 * 
 * @name - string
 * 
 * @participants - object/dictionary where key is 'id' of 'participant' model and value is 'participant'
 * 
 * @created_at - date 
 * 
 * @updated_at - date
 * 
 * @messages_tracker - object of meta data, such as:
 *      > @gotEarliestMsg - bool which describes if all messages have been fetched,
 *      > @last_message - last message in group,
 *      > @seen - have all messages been acknowledged by user
 * 
 * @whoSawWhat - dictionary where key is 'message id' and value is array of 'users ids' which saw that message last
 * 
 * @window - object for managing state of chat window
 *      > @minimized - bool, is chat window minimized or not
 *      > @showConfig - bool, is config opened or not
 *      > @hasInitMessages - bool, does chat have latest N messages,
 *      > @scrolledDownInitialy - bool, controll if scroll should go to last message on chat create
 * 
 * @typing - object for controlling who to display as currently typing
 *      > @user_ids - array of 'users id' who are being currently displayed as typing,
 *      > @timeouts - object of user ids, controlls after how long to remove user as typing by refreshing uesrs timeout when new whisper is caught by
 *          that user, preventing DOM updates during uninterrupted typing by that user. Once user stops typing for @showTyperFor time, he will be removed
 *          from array, and DOM.
 *      > @showTyperFor - int, after how long in miliseconds to remove user from @user_ids  
 * 
 * @permissions - object for controlling current user's permissions in this chat group
 *      @add - array of Role::TYPES user can add to group // not used so far... 
 *      @change_group_name - bool, can this user change group name
 *      @change_group_type - bool, can this user change group type
 *      @change_role - dictionary where key is role that can be changed  and value is role to which role can be changed of particular user
 *      @remove - array of roles this user can remove
 *      @send_message - bool, can send message or not (only case where user cannot send message is if he is LISTENER in PUBLIC_CLOSED group)
 */