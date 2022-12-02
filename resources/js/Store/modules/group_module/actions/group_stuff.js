import store from '@/Store/index.js'
import * as h from '@/Store/functions/helpers.js'
import * as ev from '@/Store/modules/group_module/group_events_namespaces.js'

const root = {root:true}

export default {
    initGroup({ getters, commit, dispatch }, group){
        commit('id',           group.id)
        commit('model_type',   group.model_type)
        commit('name',         group.name)
        commit('created_at',   group.created_at)
        commit('updated_at',   group.updated_at)

        commit('messagesM/addMessages',  group?.messages ? group.messages : [])
        commit('messagesM/last_message', group?.last_message ? group.last_message : {})
        commit('messagesM/group_id', group.id)

        commit('participantsM/group_id', group.id)
        commit('participantsM/addParticipants', group?.participants ? group.participants : [])

        dispatch('registerGroupEventListeners')
        dispatch('participantsM/registerParticipantsEventListeners')
        dispatch('messagesM/registerMessagesEventListeners')
        dispatch('messagesM/evalSeenState')

        return dispatch('createPermissions')
    },

    scrolledDownInitialy({commit}, bool){
        commit('scrolledDownInitialy', bool)
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
        dispatch(ns.groupsManager('removeFilteredGroupsId'), state.id, root)
        
        dispatch(ns.groupsManager('closeGroup'), state.id, root).then(()=>{
            dispatch(ns.groupsManager('removeGroupId'), state.id, root).then(()=> {
                Echo.leave(ev.groupChannel(state.id))

                store.unregisterModule(ns.groupModule(state.id))

                dispatch(ns.actionResponseManager('unregisterModule'), `config.groupId_${state.id}`, root)
                dispatch(ns.actionResponseManager('unregisterModule'), `groupId_${state.id}`       , root)
            })
        })
    },

    registerGroupEventListeners({ state, getters, rootState, rootGetters, commit, dispatch }){
        Echo.private(ev.groupChannel(state.id))
            .listen(ev.newGroupName(), e => {
                dispatch('changeGroupNameEvent', e.data)
            })

            .listen(ev.groupModelTypeChange(), e => {
                commit('model_type', e.data.model_type)
                dispatch('createPermissions')
            })
    },

}