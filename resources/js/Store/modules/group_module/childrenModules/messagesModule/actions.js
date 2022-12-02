import * as h from '@/Store/functions/helpers.js'
import * as ev from '@/Store/modules/group_module/group_events_namespaces.js'
const root = {root:true}

const actions = {
    getInitMessages({ state, commit, dispatch, rootGetters }){

        if(state.messages_tracker.hasInitMessages) return 

        dispatch(ns.groupModule(state.group_id, 'scrolledDownInitialy'), false, root)
        // commit('scrolledDownInitialy', false)

        return new Promise((resolve, reject) => {
            axios.get(`chat/message/latest-messages`, { params: { group_id: state.group_id } }).then(res => {
                commit('addMessages',  res.data)
                
                if(res.data.length < rootGetters[ns.chatRules('init_num_messages')]) commit('gotEarliestMsg', true)

                dispatch(ns.groupModule(state.group_id, 'scrolledDownInitialy'), true, root) 
                commit('hasInitMessages', true)
                dispatch('whoSawWhat')
                
                resolve(res)
            }).catch( error => { 
                dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                    ...error.response.data,
                    ...{
                        responseContext:{
                            moduleName: `groupId_${state.group_id}`,
                            important: true
                        }
                    } 
                }, root)
                
                reject(error)
            })
        })
    },

    allMessagesSeen({ state, commit }){
        axios.post('chat/message/seen', {
            'group_id': state.group_id,
            'message_id_saw': state.messages_tracker.last_message.id,
        })
    },

    storeMessage({ commit, dispatch, state }, message){
        message.group_id = state.group_id
        // h.logMSitTookToExecStoreMsg()

        return new Promise((resolve, reject) => {
            axios.post('chat/message/store', message).then(res => {

                resolve(res)  
            }).catch( error => { 
                dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                    ...error.response.data,
                    ...{
                        responseContext:{
                            moduleName: `groupId_${state.group_id}`,
                            important: true
                        }
                    } 
                }, root)
                
                reject(error)
            })
        })
    },

    getOlderMessages({ state, commit, getters, dispatch, rootGetters }){
        if(state.messages_tracker.gotEarliestMsg) return // All old messages are in store

        return axios.get(`chat/message/before-message`, { 
            params: { 
                group_id: state.group_id,
                earliest_msg_id: getters.getLastPossesedMsgId 
            } 
        }).then(res => {
            commit('addMessages', res.data)

            if(res.data.length < rootGetters[ns.chatRules('earliest_num_messages')]) commit('gotEarliestMsg', true)

        }).catch( error => { 
            dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `groupId_${state.group_id}`,
                        important: true
                    }
                } 
            }, root)
        })
    },

    seenEvent({ rootState, commit, dispatch }, data){
        if(rootState.auth.user.id == data.user_id) commit('seen', true)

        dispatch(ns.groupsManager('numGroupsWithUnseen'), null, root)
    },

    newMessageEvent({ rootState, commit, dispatch }, message){
        if(rootState.auth.user.id == message.user_id) commit('seen', true)

        commit('last_message', message)
        commit('addMessages', [message])

        dispatch('evalSeenState')

        dispatch(ns.groupsManager('numGroupsWithUnseen'), null, root)
        dispatch(ns.groupsManager('sortNewstGroups'),     null, root)
    },

    /**
     * Creates a dictionary @whoSawWhat where key is message id for each participant and value is array of users ids who saw that message as 'last'
     */
    whoSawWhat({ state, commit, getters, rootGetters }){
        let participants = rootGetters[ns.groupModule(state.group_id, 'participantsM/participants')]

        let whoSawWhat = {} 
        let last_message_seen_id = null

        for(let participant_id in participants){
            last_message_seen_id = participants[participant_id].pivot.last_message_seen_id

            if(last_message_seen_id){ 
                if(getters.messageExists(last_message_seen_id)){
                    if(!whoSawWhat.hasOwnProperty(last_message_seen_id)) 
                        whoSawWhat[last_message_seen_id] = []

                    whoSawWhat[last_message_seen_id].push(participant_id)
                } 
                else if(getters.messageDeleted(last_message_seen_id) && getters.numberOfMessages){
                    // move participant seen to last message in chat because last message he saw was deleted
                    let lastMessageIdBeforeOneWhichWasDeleted = getters.lastMessageIdBeforeOneWhichWasDeleted(last_message_seen_id)

                    if(!whoSawWhat.hasOwnProperty(lastMessageIdBeforeOneWhichWasDeleted)) 
                        whoSawWhat[lastMessageIdBeforeOneWhichWasDeleted] = []

                    whoSawWhat[lastMessageIdBeforeOneWhichWasDeleted].push(participant_id)
                } else {
                    // @todo no idea
                }
            } 
        }

        commit('whoSawWhat', whoSawWhat)
    },

    deleteMessage({ dispatch, state }, payload){
        payload.group_id = state.group_id

        if(payload.message_id == state.messages_tracker.last_message.id)
            payload.isLastMessage = true

        axios.post(`chat/message/delete`, payload).then(res => {
            //
        }).catch( error => { 
            dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `groupId_${state.group_id}`,
                        important: true
                    }
                } 
            }, root)
        })
    },

    evalSeenState({ state, commit, rootState, rootGetters}){
        // If user still hasn't accepted to join to chat, treat this as unseen
        if(!rootGetters[ns.groupModule(state.group_id, 'participantsM/getMyAccepted')]) {
            commit('seen', false)
            return
        }

        let last_message = state.messages_tracker.last_message

        // If there are no messages, seen
        if(_.isEmpty(last_message)) {
            commit('seen', true)
            return
        } 

        // if self is owner of last message
        if(rootState.auth.user.id == last_message.user_id) {
            commit('seen', true)
            return
        }
        
        // has user already acknowledged last message 
        commit('seen', rootGetters[ns.groupModule(state.group_id, 'participantsM/myLastMessageSeenId')] >= last_message.id)
        return 
    },

    updateMessage({dispatch, state}, payload){
        payload.group_id = state.group_id

        return new Promise((resolve, reject) => {
            axios.patch(`chat/message/update`, payload).then(res => {
                resolve(res)
            }).catch( error => { 
                dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                    ...error.response.data,
                    ...{
                        responseContext:{
                            moduleName: `groupId_${state.group_id}`,
                            important: true
                        }
                    } 
                }, root)

                reject(error)
            })
        })
    },

    registerMessagesEventListeners({ state, rootGetters, getters, commit, dispatch }){
        Echo.private(ev.groupChannel(state.group_id))
            .listen(ev.newMessage(), e => {
                // h.logMSitTookToExecStoreMsg(true) 
                commit(ns.groupModule(state.group_id, 'updated_at'), h.nowISO(), root)

                commit(ns.groupModule(state.group_id, 'participantsM/updatePivot'), {
                    last_message_seen_id: e.data.id,
                    participant_id: e.data.user_id,
                    now: h.nowISO()
                }, root)
                
                dispatch('newMessageEvent', e.data).then(()=>{
                    if(rootGetters[ns.groupModule(state.group_id, 'participantsM/getMyAccepted')]){
                        dispatch(ns.groupsManager('openGroup'), {
                            group_id: state.group_id,
                            initiatedBy: 'system'
                        }, root)
                    }

                    dispatch('whoSawWhat')
                })
            })

            .listen( ev.messageSeen(), e => {
                commit(ns.groupModule(state.group_id, 'participantsM/updatePivot'), {
                    last_message_seen_id: e.data.last_message_seen_id,
                    participant_id: e.data.user_id,
                    now: h.nowISO()
                }, root)

                dispatch('seenEvent', e.data).then(() => {
                    dispatch('whoSawWhat')
                })
            })

            .listen(ev.messageUpdate(), e => {
                commit('addMessages', [e.data])

                if(getters['last_message'].id == e.data.id) commit('last_message', e.data)
            })

            .listen(ev.deletedMessage(), e => {
                commit('deleteMessage', e.data.message_id)

                // If server responded with cinfirmation that deleted message is latest, check its content
                // if empty that means it was last message in group
                if(e.data.hasOwnProperty('latest_message_after_delete') ){
                    commit('last_message', e.data.latest_message_after_delete 
                        ? e.data.latest_message_after_delete 
                        : {}
                    )
                }

                dispatch('evalSeenState')
                dispatch(ns.groupsManager('numGroupsWithUnseen'), null, root)
                dispatch('whoSawWhat')
            })
    },

}

export default actions 