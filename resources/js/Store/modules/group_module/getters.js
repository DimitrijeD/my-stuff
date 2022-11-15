import * as h from '@/Store/functions/helpers.js';

const getters = 
{
    state: (state) => state,

    id: (state) => state.id,

    /**
     * Dictionary where keys are ids of messages
     */
    messages:   (state) => state.messages,

    model_type: (state) => state.model_type,

    name:       (state) => state.name,

    /**
     * Dictionary where keys are ids of users
     */
    participants: (state) => state.participants,

    created_at:   (state) => state.created_at,

    updated_at:   (state) => state.updated_at,

    /**
     * Is chat window minimized or not
     */
    minimized: (state) => state.window.minimized,

    /**
     * Is chat window config opened
     */
    showConfig: (state) => state.window.showConfig,

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

    getUserRole:             (state) => (participant_id) => state.participants[participant_id].pivot.participant_role,

    getParticipant:          (state) => (participant_id) => state.participants[participant_id],
    
    getParticipantThumbnail: (state) => (participant_id) => state.participants[participant_id].thumbnail,

    /**
     * Number of messages in state.messages
     */
    getPossesedNumberOfMessagesInGroup: (state) => Object.keys(state.messages).length,

    /**
     * Id of oldest message stored in state.messages
     */
    getLastPossesedMsgId: (state) => h.getMinObjKey(state.messages),

    /**
     * Id of message participant saw last
     */
    last_message_seen_id: (state) => (participant_id) => state.participant[participant_id].pivot.last_message_seen_id,

    /**
     * Array of users ids who are currently typing
     */
    usersTyping: (state) => state.typing.user_ids,

}

export default getters 