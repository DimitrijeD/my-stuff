import * as h from '@/Store/functions/helpers.js'
const root = {root:true}

export default {
    getInitMessages({ state, commit, dispatch, rootState }){
        if(state.window.hasInitMessages) return 

        commit('scrolledDownInitialy', false)

        return new Promise((resolve, reject) => {
            axios.get(`chat/group/${state.id}/latest-messages`).then(res => {
                commit('addMessages',  res.data)

                dispatch('shouldLockEarliestMsges', {
                    response_length: res.data.length,
                    rule_length: rootState[ns.chat_rules()].init_num_messages
                })

                commit('scrolledDownInitialy', true)
                commit('hasInitMessages', true)
                dispatch('whoSawWhat')
                
                resolve(res)
            }).catch( error => { 
                dispatch('makeActionResponseMessage', {
                    ...error.response.data,
                    ...{
                        responseContext:{
                            moduleName: `groupId_${state.id}`,
                            important: true
                        }
                    } 
                })
                
                reject(error)
            })
        })
    },

    allMessagesSeen({ state, commit }, lastMessageId){
        axios.post('chat/message/seen', {
            'group_id': state.id,
            'lastMessageId': lastMessageId,
        }).then(res => {
            commit('seen', true)
        })
    },

    storeMessage({ commit, dispatch, state }, message){
        message.group_id = state.id

        return new Promise((resolve, reject) => {
            axios.post('chat/message/store', message).then(res => {
                commit('updatePivot', {
                    last_message_seen_id: res.data.id,
                    participant_id: res.data.user_id, 
                    now: h.nowISO(),
                }) 
                commit('seen', true)
                dispatch(ns.groupsManager('numGroupsWithUnseen'), null, root)

                resolve(res)  
            }).catch( error => { 
                dispatch('makeActionResponseMessage', {
                    ...error.response.data,
                    ...{
                        responseContext:{
                            moduleName: `groupId_${state.id}`,
                            important: true
                        }
                    } 
                })
                
                reject(error)
            })
        })
    },

    getOlderMessages({ state, commit, getters, dispatch, rootState }){
        if(state.messages_tracker.gotEarliestMsg) return // All old messages are in store

        return axios.get(`chat/group/${state.id}/before-msg/${getters.getLastPossesedMsgId}`).then(res => {
            commit('addMessages', res.data)

            dispatch('shouldLockEarliestMsges', {
                response_length: res.data.length,
                rule_length: rootState[ns.chat_rules()].earliest_num_messages
            })

        }).catch( error => { 
            dispatch('makeActionResponseMessage', {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `groupId_${state.id}`,
                        important: true
                    }
                } 
            })
        })
    },

    shouldLockEarliestMsges({ commit }, data){
        if(data.response_length < data.rule_length) commit('gotEarliestMsg', true)
    },

    seenEvent({ rootState, commit, dispatch }, data){
        commit('updatePivot', {
            last_message_seen_id: data.last_message_seen_id,
            participant_id: data.user_id,
            now: h.nowISO()
        })

        // this event is triggered by this.user,  meaning he is the one who saw last message
        if(rootState.auth.user.id == data.user_id) commit('seen', true)

        dispatch(ns.groupsManager('numGroupsWithUnseen'), null, root)

    },

    newMessageEvent({ rootState, getters, commit, dispatch }, message){

        commit('last_message', message)
        commit('addMessages', [message])
        commit('updated_at', h.nowISO())

        dispatch('evalSeenState')

        commit('updatePivot', {
            last_message_seen_id: message.id,
            participant_id: message.user_id,
            now: h.nowISO()
        })

        dispatch(ns.groupsManager('numGroupsWithUnseen'), null, root)
        dispatch(ns.groupsManager('sortNewstGroups'),     null, root)
    },


    /**
     * When group is instantiated
     * When new messages is received
     * When some1 sees message
     */
     whoSawWhat({ state, rootState, commit, dispatch, getters, rootGetters }){
        let whoSawWhat = {} 
        let last_message_seen_id = null

        for(let i in state.participants){
            last_message_seen_id = state.participants[i].pivot.last_message_seen_id
        
            if(last_message_seen_id){ 
                if(!whoSawWhat.hasOwnProperty(last_message_seen_id)) whoSawWhat[last_message_seen_id] = []

                whoSawWhat[last_message_seen_id].push(state.participants[i].id)
            } 
        }
        
        commit('whoSawWhat', whoSawWhat)
    },

    deleteMessage({ commit, dispatch, state }, payload){
        payload.group_id = state.id

        if(payload.message_id == state.messages_tracker.last_message.id)
            payload.isLastMessage = true

        axios.post(`chat/message/delete`, payload).then(res => {
            //
        }).catch( error => { 
            dispatch('makeActionResponseMessage', {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `groupId_${state.id}`,
                        important: true
                    }
                } 
            })
        })
    },

    evalSeenState({getters, state, commit, rootState}){
        let last_message = state.messages_tracker.last_message

        // By doing this all burden of determining is user saw all messages in group is pushed to 'last_message'
        // meaning if code is dog by not managing last message propertly, this will always return true on chat init
        if(!last_message) {
            commit('seen', true)
            return
        }

        // if self is owner of last message
        if(rootState.auth.user.id == last_message.user_id) {
            commit('seen', true)
            return
        }
        
        // has user already acknowledged last message 
        commit('seen', getters.myLastMessageSeenId >= last_message.id)
    },

    updateMessage({dispatch, state}, payload){
        payload.group_id = state.id

        return new Promise((resolve, reject) => {
            axios.patch(`chat/message/update`, payload).then(res => {
                resolve(res)
            }).catch( error => { 
                dispatch('makeActionResponseMessage', {
                    ...error.response.data,
                    ...{
                        responseContext:{
                            moduleName: `groupId_${state.id}`,
                            important: true
                        }
                    } 
                })

                reject(error)
            })
        })
    }

}