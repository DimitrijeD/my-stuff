import * as h from '@/Store/functions/helpers.js'
import * as actionMessageFormater from  '@/Store/modules/group_module/actionMessageFormater.js'

const root = {root:true}

export default {

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
            dispatch('createPermissions')
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
                }, root) 
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

}