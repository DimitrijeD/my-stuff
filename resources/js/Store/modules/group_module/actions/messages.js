import * as h from '@/Store/functions/helpers.js'
const root = {root:true}

export default {
    getInitMessages({ state, commit, dispatch, rootState }){
        if(state.window.hasInitMessages) return 

        commit('scrolledDownInitialy', false)

        return axios.get(`chat/group/${state.id}/latest-messages`).then(res => {
            commit('addMessages',  res.data)

            dispatch('shouldLockEarliestMsges', {
                response_length: res.data.length,
                rule_length: rootState[ns.chat_rules()].init_num_messages
            })

            commit('scrolledDownInitialy', true)
            commit('hasInitMessages', true)
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

    allMessagesSeen({ state, commit }, lastMessageId){
        axios.post('chat/message/seen', {
            'group_id': state.id,
            'lastMessageId': lastMessageId,
        }).then(res => {
            commit('seen', true)
        })
    },

    storeMessage({ commit, dispatch, state }, message){
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

    newMessageEvent({ rootState, commit, dispatch }, msg){
        commit('last_message', msg)
        commit('addMessages', [msg])
        commit('updated_at', h.nowISO())

        const selfId = rootState.auth.user.id

        // if message is "mine" set seen to true, 
        // else set to false
        commit('seen', selfId == msg.user_id)

        commit('updatePivot', {
            last_message_seen_id: msg.id,
            participant_id: msg.user_id,
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

}