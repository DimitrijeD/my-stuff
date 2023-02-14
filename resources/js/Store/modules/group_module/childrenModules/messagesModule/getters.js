import * as h from '@/Store/functions/helpers.js'

const getters = {
    group_id: (state) => state.group_id,

    messages: (state) => state.messages,

    hasInitMessages: (state) => state.messages_tracker.hasInitMessages,

    /**
     * Boolean, have all messages been fetched
     * 
     * More precisely, have fetching older messages resulted in receiving less then expected number of messages. 
     */
    reachedEarliestMsgId: (state) => state.messages_tracker.reachedEarliestMsgId,

    last_message: (state) => state.messages_tracker.last_message,

    /**
     * Boolean, has user seen all messages in chat
     * 
     * true -> yes he did
     * false -> no he didn't 
     */
    seen: (state) => state.messages_tracker.seen,

    messages_tracker: (state) => state.messages_tracker,

    /**
     * Id of oldest message stored in state.messages
     */
    getLastPossesedMsgId: (state) => h.getMinObjKey(state.messages),

    messageById: (state) =>(id) => state.messages[id],

    /**
     * Has message been fetched from API or not
    */
    messageExists: (state) =>(id) => state.messages[id] ? id : false,

    /**
     * Has message been deleted or not
     * 
     * This can only be determined if provided @param id is in range [min id in messages, max id in messages]
     * 
     * @return {Boolean} 
     *      true - message deleted
     *      false - message not deleted
    */
    messageDeleted: (state, getters) => (id) => {
        // does message already exists in store
        if(getters.messageExists(id)) return false

        let range = getters.messagesRange

        // if there are not messages or if there is only one message
        if(range.length == 0 || range[0] == range[1]) return false

        // if id is in store.messages range or id is greater then
        return id >= range[0] 
            ? true
            : false
    },

    // need to include self messsages here @todo
    lastMessageIdBeforeOneWhichWasDeleted: (state, getters) => (before_id) => h.getClosestMin(Object.keys(state.messages), before_id),

    /**
     * Returns array of 2 numbers where 0th element is oldest message id, 1th element is latest message id
    */
    messagesRange: (state) => h.getMinAndMax(Object.keys(state.messages)) ,

    whoSawWhat: (state) => state.whoSawWhat,

    numberOfMessages: (state) => Object.keys(state.messages).length,

    messageFiles: (state) => (id) => state.messages[id]?.files ?? [],
}

export default getters 