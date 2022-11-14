import * as h from '@/Store/functions/helpers.js';
import * as ns from '@/Store/module_namespaces.js';
import * as actionMessageFormater from  './actionMessageFormater.js';

import store from '@/Store/index.js';

const actions = {
    initGroup({ state, rootState, commit }, group){
        const selfId = rootState.auth.user.id
        if(group?.id)           commit('id',           group.id)
        if(group?.last_message) commit('last_message', group.last_message)
        if(group?.messages)     commit('addMessages',  group.messages)
        if(group?.model_type)   commit('model_type',   group.model_type)
        if(group?.name)         commit('name',         group.name)
        if(group?.created_at)   commit('created_at',   group.created_at)
        if(group?.updated_at)   commit('updated_at',   group.updated_at)

        if(group?.participants){
            commit('addParticipants', group.participants)
            commit('seen', h.evalSeenState(
                h.getById(group.participants, selfId), 
                group?.last_message
            ))
        }

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
                dispatch(ns.groupsManager('numGroupsWithUnseen'), null, {root:true})

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

    scrolledDownInitialy({ state, commit, getters, dispatch, rootState }, bool){
        commit('scrolledDownInitialy', bool)
    },

    getEarliestMessages({ state, commit, getters, dispatch, rootState }){
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

    addParticipants({ state, dispatch }, data){
        data.group_id = state.id
        
        return axios.post(`chat/group/${state.id}/add-users`, h.prepareParticipantsForStoreRequest(data)).then(res => {
            dispatch('makeActionResponseMessage', {
                ...res.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.id}`,
                        important: true
                    }
                } 
            })
        }).catch( error => { 
            dispatch('makeActionResponseMessage', {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.id}`,
                        important: true
                    }
                } 
            }) 
        })
    },

    removeParticipant({ state, dispatch }, participant_id){
        axios.post(`chat/group/remove-user`, {
            group_id: state.id,
            remove_user_id: participant_id
        }).then(res => {
            dispatch('makeActionResponseMessage', {
                ...res.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.id}`,
                        important: false
                    }
                } 
            }) 
        }).catch( error => { 
            dispatch('makeActionResponseMessage', {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.id}`,
                        important: true
                    }
                } 
            })
        })
    },

    leaveGroup({ state, dispatch }){
        axios.get(`chat/group/${state.id}/leave`).then((res) => {
            dispatch('purgeGroup') 
            dispatch(ns.actionResponseManager('provide'), {
                ...res.data,
                ...{
                    responseContext:{
                        moduleName: `main`,
                        important: true
                    }
                } 
            }, {root: true}) 
        }).catch(error => { 
            dispatch('makeActionResponseMessage', {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.id}`,
                        important: true
                    }
                } 
            }) 
        })
    },

    changeParticipantRole({ state, dispatch }, data){
        data.group_id = state.id
        
        axios.post("chat/group/change-user-role", data).then(res => {
            dispatch('makeActionResponseMessage', {
                ...res.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.id}`,
                        important: false
                    }
                } 
            }) 
        }).catch(error => { 
            dispatch('makeActionResponseMessage', {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.id}`,
                        important: true
                    }
                } 
            }) 
        })
    },

    seenEvent({ rootState, commit, dispatch }, data){
        commit('updatePivot', {
            last_message_seen_id: data.last_message_seen_id,
            participant_id: data.user_id,
            now: h.nowISO()
        })

        // this event is triggered by this.user,  meaning he is the one who saw last message
        if(rootState.auth.user.id == data.user_id) commit('seen', true)

        dispatch(ns.groupsManager('numGroupsWithUnseen'), null, {root:true})

    },

    updateParticipantRoleEvent({ commit, dispatch, rootState, state }, data){
        commit('updateParticipantRole', {
            participant_id: data.pivot.user_id,
            participant_role: data.pivot.participant_role
        })

        // Show only to user which role has been changed
        if(rootState.auth.user.id == data.pivot.user_id){
            dispatch('makeActionResponseMessage', {
                ...data,
                ...{
                    responseContext:{
                        moduleName: `groupId_${state.id}`,
                        important: true
                    }
                } 
            })
        }
    },

    removedParticipantEvent({ rootState, commit, dispatch }, data){
        if(rootState.auth.user.id == data.removed_user_id){
            dispatch('purgeGroup').then(() => {

                dispatch(ns.actionResponseManager('provide'), {
                    ...data,
                    ...{
                        responseContext:{
                            moduleName: `main`,
                            important: true
                        }
                    } 
                }, {root:true}) 
            })
        } else {
            commit('deleteParticipant', data.removed_user_id)
        }
    },

    addedParticipantEvent({ commit, dispatch, state }, data){
        commit('addParticipants', data.participants)

        dispatch('makeActionResponseMessage', {
            ...actionMessageFormater.addedNewParticipants({
                addedUsers: data.addedUsers,
                participants: data.participants
            }),
            ...{
                responseContext:{
                    moduleName: `groupId_${state.id}`,
                    important: true
                }
            } 
        })
    },

    participantLeftGroupEvent({ commit }, data){
        commit('deleteParticipant', data.user_left_id)
    },

    changeGroupName({ state }, data){
        axios.post("chat/group/change-group-name", data)
    },

    changeGroupNameEvent({ commit }, data){
        commit('name', data.new_name)
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

        dispatch(ns.groupsManager('numGroupsWithUnseen'), null, {root:true})
        dispatch(ns.groupsManager('sortNewstGroups'),     null, {root:true})
    },

    listenForNewMessages({ dispatch }, group_id){
        Echo.private(`group.${group_id}`).listen('.message.new', e => {
            dispatch('newMessageEvent', e.data).then(()=>{
                dispatch('whoSawWhat')
            })
        })
    },

    // refreshGroup({ state, commit, dispatch }, data)
    // {
    //     axios.get(`chat/group/refresh/${state.id}`).then(res => {
    //         // commit('refreshGroupParticipants', res.data.participants)
    //         commit('addMessages', res.data.latest_messages)
    //         dispatch(ns.groupsManager() + '/sortNewstGroups', null, {root:true})
    //     })
    // },

    purgeGroup({ state, dispatch }){
        const group_id = state.id 
        dispatch(ns.groupsManager('removeFilteredGroupsId'), group_id, {root:true})
        
        dispatch(ns.groupsManager('closeGroup'), group_id, {root:true}).then(()=>{
            dispatch(ns.groupsManager('removeGroupId'), group_id, {root:true}).then(()=> {
                Echo.leave(`group.${group_id}`)
                store.unregisterModule(ns.groupModule(group_id))
            })
        })
        
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

    registerEventListeners({ state, rootState, commit, dispatch, getters, rootGetters }){
        Echo.private(`group.${state.id}`)
            .listen('.message.seen', e => {
                dispatch('seenEvent', e.data).then(() => {
                    dispatch('whoSawWhat')
                })
            })

            .listen('.participant.role.change', e => {
                dispatch('updateParticipantRoleEvent', e.data)
            })

            .listen('.participant.removed', e => {
                dispatch('removedParticipantEvent', e.data)
            })

            .listen('.participant.left', e => {
                dispatch('participantLeftGroupEvent', e.data)
            })

            .listen('.group.new_name', e => {
                dispatch('changeGroupNameEvent', e.data)
            })

            .listen('.participant.added', e => {
                let isAmongThem = false
                const selfId = rootState.auth.user.id
                
                for(let i in e.data.addedUsers){
                    if(e.data.addedUsers[i].user_id == selfId){
                        isAmongThem = true
                        break
                    }
                }

                if(isAmongThem){
                    // @todo - user listens for event which he shouldn't be listening to since he was no longer participant in chat group.
                } else {
                    dispatch('addedParticipantEvent', e.data)
                }

            })
            
            .listen('.group.model_type', e => {
                commit('model_type', e.data.model_type)
            })
    },

    toggleWindow({commit}){
        commit('toggleWindow')
    },

    toggleConfig({commit}){
        commit('toggleConfig')
    },

    /**
     * Action responsible for providing ActionResponse card with message content only if chat is opened (not minimized)
     */
    makeActionResponseMessage({ state, dispatch }, data){
        if(!state.window.minimized){
            dispatch( ns.actionResponseManager('provide'), data, {root:true} )
        } 
    },

    changeGroupType({ state, dispatch }, payload){
        if(!payload.group_id || !payload.model_type) return       

        return new Promise((resolve, reject) => {
            axios.post("chat/group/change-group-type", payload).then((res) => {
                dispatch('makeActionResponseMessage', {
                    ...res.data,
                    ...{
                        responseContext:{
                            moduleName: `config.groupId_${state.id}`,
                            important: true
                        }
                    } 
                }) 

                resolve(res)  
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
            
            reject(error)
        })
    },
    
}

export default actions