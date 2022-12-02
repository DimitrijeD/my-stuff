import * as h from '@/Store/functions/helpers.js'

const getters = {
    participants: (state) => state.participants,

    getUserRole: (state) => (participant_id) => state.participants[participant_id].pivot.participant_role,

    /**
     * Boolean getter for has user accepted to get access and comunicate in chat
     */
    getMyAccepted: (state, getters, rootState) => state.participants[rootState.auth.user.id].pivot.accepted,

    /**
     * Returns user who invited this.user into this.group
     */
    myInviter: (state, getters, rootState) => state.participants[ state.participants[rootState.auth.user.id].pivot.invited_by_user_id ],

    /**
     * Get role of this.user in this.group
     */
    getMyRole: (state, getters, rootState) => state.participants[rootState.auth.user.id].pivot?.participant_role,

    getParticipant: (state, getters, rootState, rootGetters) => (participant_id) => {
        if(state.participants.hasOwnProperty(participant_id)) return state.participants[participant_id]

        return rootGetters[ns.users('defaultUser')]
    },

    getParticipantThumbnail: (state) => (participant_id) => state.participants[participant_id]?.thumbnail,

    /**
     * Id of message participant saw last
     */
    last_message_seen_id: (state) => (participant_id) => state.participants[participant_id].pivot.last_message_seen_id,

    /**
     * Get id of message this.user has seen last
     */
    myLastMessageSeenId: (state, getters, rootState) => state.participants[rootState.auth.user.id].pivot.last_message_seen_id,

    /**
     * Array of users ids who are currently typing
     */
    usersTyping: (state) => state.typing.user_ids,

    participantsIds:      (state) => Object.keys(state.participants),
    numberOfParticipants: (state) => Object.keys(state.participants).length,
}

export default getters 