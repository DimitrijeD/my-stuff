import defaultState from './defaultState.js'
import * as h from '@/Store/functions/helpers.js'

const mutations = {
    group_id: (state, id) => state.group_id = id,

    whoSawWhat: (state, object) => state.whoSawWhat = object,

    gotEarliestMsg:  (state, bool)   => state.messages_tracker.gotEarliestMsg  = bool,
    hasInitMessages: (state, bool)   => state.messages_tracker.hasInitMessages = bool, 
    seen:            (state, bool)   => state.messages_tracker.seen            = bool,
    last_message:    (state, message) => state.messages_tracker.last_message   = message,
    
    addMessages: (state, messages) => state.messages = { ...state.messages, ...h.createDict(messages, 'id') },

    deleteMessage: (state, message_id) => {
        if(state.messages[message_id]) delete state.messages[message_id]
    },

    deleteLastMessage: (state) => state.messages_tracker.last_message = {},

}

export default mutations 