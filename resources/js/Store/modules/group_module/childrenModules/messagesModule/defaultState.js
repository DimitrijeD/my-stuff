export default () => { 
    return {
        group_id: null,
        messages_tracker: {
            hasInitMessages: false,
            gotEarliestMsg: false,
            last_message: {},
            seen: null,
        },
        whoSawWhat: {},
        messages: {},
    }
}

/**
 * @messages - object/dictionary where key is 'id' of 'message' model and value is 'message'.  Message model contains:
 *      > @id
 *      > @text
 *      > @user_id
 *      > @created_at
 *      > @updated_at
 * 
 * @messages_tracker - object of meta data, such as:
 *      > @gotEarliestMsg - bool which describes if all messages have been fetched,
 *      > @last_message - last message in group,
 *      > @seen - have all messages been acknowledged by user
 * 
 * @whoSawWhat - dictionary where key is 'message id' and value is array of 'users ids' which saw that message last
 */