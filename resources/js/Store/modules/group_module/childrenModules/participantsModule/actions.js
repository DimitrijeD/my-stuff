import * as h from '@/Store/functions/helpers.js'
import * as ev from '@/Store/modules/group_module/group_events_namespaces.js'
import * as actionMessageFormater from  '@/Store/modules/group_module/actionMessageFormater.js'

const root = {root:true}

const actions = {
    addParticipants({ state, dispatch }, data){
        data.group_id = state.group_id
        
        return axios.post(`chat/group/${state.group_id}/add-users`, h.prepareParticipantsForStoreRequest(data)).then(res => {
            dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                ...res.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.group_id}`,
                        important: true
                    }
                } 
            }, root)
        }).catch( error => { 
            dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.group_id}`,
                        important: true
                    }
                } 
            }, root) 
        })
    },

    removeParticipant({ state, dispatch }, participant_id){
        axios.post(`chat/group/remove-user`, {
            group_id: state.group_id,
            remove_user_id: participant_id
        }).then(res => {
            dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                ...res.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.group_id}`,
                        important: false
                    }
                } 
            }, root) 
        }).catch( error => { 
            dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.group_id}`,
                        important: true
                    }
                } 
            }, root)
        })
    },

    changeParticipantRole({ state, dispatch }, data){
        data.group_id = state.group_id
        
        axios.post("chat/group/change-user-role", data).then(res => {
            dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                ...res.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.group_id}`,
                        important: false
                    }
                } 
            }, root) 
        }).catch(error => { 
            dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `config.groupId_${state.group_id}`,
                        important: true
                    }
                } 
            }, root) 
        })
    },

    updateParticipantRoleEvent({ commit, dispatch, rootState, state }, data){
        commit('updateParticipantRole', {
            participant_id: data.pivot.user_id,
            participant_role: data.pivot.participant_role
        })

        // Show only to user which role has been changed
        if(rootState.auth.user.id == data.pivot.user_id){
            dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                ...data,
                ...{
                    responseContext:{
                        moduleName: `groupId_${state.group_id}`,
                        important: true
                    }
                } 
            }, root)

            dispatch(ns.groupModule(state.group_id, 'createPermissions'), null, root)
        }
    },

    removedParticipantEvent({ rootState, commit, dispatch, getters, state }, data){
        if(rootState.auth.user.id == data.removed_user_id){
            dispatch(ns.groupModule(state.group_id, 'purgeGroup'), null, root).then(() => {

                if(!getters.getMyAccepted) return // If user hasn't yet accepted to join chat, do not show following message
                
                dispatch(ns.actionResponseManager('provide'), {
                    ...data,
                    ...{
                        responseContext:{
                            moduleName: `main`,
                            important: true
                        }
                    } 
                }, root) 
            }, root)
        } else {
            commit('deleteParticipant', data.removed_user_id)
        }
    },

    addedParticipantEvent({ commit, dispatch, state }, data){
        commit('addParticipants', data.participants)

        dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
            ...actionMessageFormater.addedNewParticipants({
                addedUsers: data.addedUsers,
                participants: data.participants
            }),
            ...{
                responseContext:{
                    moduleName: `groupId_${state.group_id}`,
                    important: true
                }
            } 
        }, root)
    },

    participantLeftGroupEvent({ commit }, data){
        commit('deleteParticipant', data.user_left_id)
    },

    responseToInvitationToGroup({ state,  dispatch }, responseToInvitation){
        axios.post(`chat/group/invitation-response`, {
            group_id: state.group_id,
            responseToInvitation: responseToInvitation
        }).then(res => {
            
        }).catch( error => { 
            dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `main`,
                        important: true
                    }
                } 
            }, root)
            
        })
    },

    registerParticipantsEventListeners({ state, rootState, commit, dispatch }){
        Echo.private(ev.groupChannel(state.group_id))
            .listen(ev.participantRespondedToInvitation(), e => {
                if(e.data.accepted){
                    commit('participantAcceptedToJoinGroup', e.data.user_id)
                    dispatch(ns.groupModule(state.group_id, 'messagesM/evalSeenState'), null, root).then(() => {
                        dispatch(ns.groupsManager('numGroupsWithUnseen'), null, root)
                    })
                } else {
                    commit('deleteParticipant', e.data.user_id)

                    if(e.data.user_id == rootState.auth.user.id) {
                        dispatch(ns.groupModule(state.group_id, 'purgeGroup'), null, root).then(()=>{
                            dispatch(ns.groupsManager('numGroupsWithUnseen'), null, root)
                        })
                    }
                }

            })

            .listen(ev.participantRoleChange(), e => {
                dispatch('updateParticipantRoleEvent', e.data)
            })

            .listen(ev.participantRemoved(), e => {
                dispatch('removedParticipantEvent', e.data)
            })

            .listen(ev.participantLeft(), e => {
                dispatch('participantLeftGroupEvent', e.data)
            })

            .listen(ev.participantAdded(), e => {
                dispatch('addedParticipantEvent', e.data)
            })
    },


    toggleTypingEventListeners({ state, rootState, commit, dispatch }, toActivate){
        if(toActivate){
            Echo.private(ev.groupChannel(state.group_id))
                .listenForWhisper(ev.typing(), data => {
                    if(data.isTyping){
                        commit('addTyper', data.id)
                        commit('setTypingTimeout', data.id)
                    } else{
                        commit('removeTyper', data.id)
                    }
                })
        } else {
            Echo.private(ev.groupChannel(state.group_id))
                .stopListeningForWhisper(ev.typing())
        }
    },
}

export default actions 