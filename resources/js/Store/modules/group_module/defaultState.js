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
        permissions: {}
    }
}

/**
 * id -int
 * 
 * messages - object/dictionary where key is 'id' of 'message' model and value is 'message'
 *      - message model contains:
 *          - id
 *          - text
 *          - user_id
 *          - created_at
 *          - updated_at
 *          - Model user 
 * model_type - string
 * 
 * name - string
 * 
 * participants - object/dictionary where key is 'id' of 'participant' model and value is 'participant'
 */