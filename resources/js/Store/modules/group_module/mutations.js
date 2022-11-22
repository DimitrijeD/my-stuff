import * as h from '@/Store/functions/helpers.js';

const mutations = 
{
    id:         (state, int) => state.id = int,
    model_type: (state, string) => state.model_type = string,
    name:       (state, string) => state.name = string,
    created_at: (state, val) => state.created_at = val,
    updated_at: (state, date) => state.updated_at = date,
    whoSawWhat: (state, object) => state.whoSawWhat = object,

    gotEarliestMsg: (state, bool) => state.messages_tracker.gotEarliestMsg = bool,
    last_message:   (state, message) => state.messages_tracker.last_message = message,
    seen:           (state, bool) => state.messages_tracker.seen = bool,
    
    addMessages:     (state, messages    ) => state.messages     = { ...state.messages,     ...h.createDict(messages,     'id') },
    addParticipants: (state, participants) => state.participants = { ...state.participants, ...h.createDict(participants, 'id') },
    
    updateParticipantRole: (state, data) => state.participants[data.participant_id].pivot.participant_role = data.participant_role,

    updatePivot(state, data) {
        state.participants[data.participant_id].pivot.last_message_seen_id = data.last_message_seen_id
        state.participants[data.participant_id].pivot.updated_at           = data.now
    },

    deleteMessage     (state, message_id    ) { delete state.messages[message_id] },
    deleteParticipant (state, participant_id) { delete state.participants[participant_id] },

    toggleWindow: (state) => state.window.minimized  = !state.window.minimized,
    toggleConfig: (state) => state.window.showConfig = !state.window.showConfig,

    hasInitMessages: (state, bool) => state.window.hasInitMessages = bool,
    scrolledDownInitialy: (state, bool) => state.window.scrolledDownInitialy = bool,
    showConfig: (state, bool) => state.window.showConfig = bool,
    minimized: (state, bool) => state.window.minimized = bool,

    removeTyper(state, id){
        if(state.typing.user_ids.includes(id))
            state.typing.user_ids.splice(state.typing.user_ids.indexOf(id), 1)
    },

    addTyper(state, id){
        if(!state.typing.user_ids.includes(id))
            state.typing.user_ids.unshift(id)
    },

    setTypingTimeout(state, id){
        clearTimeout(state.typing.timeouts[id])

        state.typing.timeouts[id] = setTimeout(() => {
            // removeTyper Mutation 
            if(state.typing.user_ids.includes(id))
                state.typing.user_ids.splice(state.typing.user_ids.indexOf(id), 1)
        }, state.typing.showTyperFor)
    },

    permissions: (state, permissions) => state.permissions = permissions,

    deleteMessage: (state, message_id) => {
        if(state.messages[message_id]) delete state.messages[message_id]
    },

    deleteLastMessage: (state) => {
        state.messages_tracker.last_message = {}
    }
}

export default mutations 