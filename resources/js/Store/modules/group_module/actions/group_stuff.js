import store from '@/Store/index.js'
import * as h from '@/Store/functions/helpers.js'

const root = {root:true}

export default {
    initGroup({ state, rootState, commit, dispatch }, group){
        commit('id',           group.id)
        commit('last_message', group.last_message)
        commit('addMessages',  group.messages)
        commit('model_type',   group.model_type)
        commit('name',         group.name)
        commit('created_at',   group.created_at)
        commit('updated_at',   group.updated_at)

        commit('addParticipants', group.participants)
        commit('seen', h.evalSeenState(
            h.getById(group.participants, rootState.auth.user.id), 
            group?.last_message
        ))

        dispatch('registerEventListeners')

        return dispatch('createPermissions')
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
            }, root) 
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

    changeGroupName({ state }, data){
        axios.post("chat/group/change-group-name", data)
    },

    changeGroupNameEvent({ commit }, data){
        commit('name', data.new_name)
    },

    purgeGroup({ state, dispatch }){
        const group_id = state.id 
        dispatch(ns.groupsManager('removeFilteredGroupsId'), group_id, root)
        
        dispatch(ns.groupsManager('closeGroup'), group_id, root).then(()=>{
            dispatch(ns.groupsManager('removeGroupId'), group_id, root).then(()=> {
                Echo.leave(`group.${group_id}`)
                store.unregisterModule(ns.groupModule(group_id))
            })
        })
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

                if(!isAmongThem) dispatch('addedParticipantEvent', e.data)
            })
            
            .listen('.group.model_type', e => {
                commit('model_type', e.data.model_type)
                dispatch('createPermissions')
            })

            .listenForWhisper('typing', data => {
                if(data.isTyping){
                    commit('addTyper', data.id)
                    commit('setTypingTimeout', data.id)
                } else{
                    commit('removeTyper', data.id)
                }
            })

            .listen('.message.new', e => {
                dispatch('newMessageEvent', e.data).then(()=>{
                    dispatch('whoSawWhat')
                })
            })
    },
}