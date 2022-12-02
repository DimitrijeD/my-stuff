import * as h from '@/Store/functions/helpers.js'
import * as collection from '@/UtilityFunctions/collection.js'
import { fuzzyDeep } from '@/UtilityFunctions/fuzzyDeep.js'
import * as ev from '@/Store/modules/group_module/group_events_namespaces.js'

import store from '@/Store/index.js'
import group_module from '@/Store/modules/group_module/group_module.js'

const root = {root:true}

const actions = {
    /**
     * Entry point for chat data.
     */
     init({ dispatch }){
        dispatch('registerChatListeners')

        return axios.get('chat/init').then((res)=>{
            dispatch(ns.chatRules('setupRules'), res.data.chat_rules, root).then(() => {

                for(let i = 0; i < res.data.groups.length; i++){
                    dispatch('setupGroupModule', res.data.groups[i])
                }

            }).then(()=>{
                dispatch('sortNewstGroups')
                dispatch('numGroupsWithUnseen')
            })
        }).catch(error => {
            // Fatal error, request reload because without this successful request, chatting will not work
        })
    },

    filterGroupsBySearchString({ commit, getters }, str){
        commit('setFilteredGroupsIds', h.getAllIds( 
            fuzzyDeep(str, getters['getAllGroups'], [
                {
                    path: '',
                    props: ['name']
                },
                {
                    path: 'participantsM[participants]',
                    props: ['first_name', 'last_name']
                },
            ])
        ))
    },

    sortNewstGroups({ commit, getters }){
        commit('setFilteredGroupsIds', h.getAllIds(h.sortNewest(getters['getGroupsById'](getters['filteredGroupsIds']))))
    },

    setupGroupModule({ commit, dispatch }, group){
        let namespace = ns.groupModule(group.id)
        store.registerModule(namespace, group_module)

        commit('addGroupsIds', [group.id])
        commit('addToFilteredGroups', group.id)
        
        return dispatch(ns.groupModule(group.id, 'initGroup'), group, root).then(() => {
            dispatch('sortNewstGroups')
        })
    },

    storeGroup({ commit, dispatch }, data){
        return new Promise((resolve, reject) => {
            axios.post('chat/group/store', data).then( res => {
                dispatch('setupGroupModule', res.data).then(() => {
                    dispatch('sortNewstGroups')
                    commit('openWindow', res.data.id)

                    commit(ns.groupModule(res.data.id, 'messagesM/gotEarliestMsg'), true, root)
                    commit(ns.groupModule(res.data.id, 'messagesM/hasInitMessages'), true, root)
                    commit(ns.groupModule(res.data.id, 'messagesM/seen'), true, root)
                })
                resolve(res)
            }).catch((error) =>{
                dispatch(ns.actionResponse('createChatGroup', 'inject'), error.response.data, root)
                reject(error)
            })
        })
    },

    openGroup({ commit, dispatch, getters, rootState }, { group_id, initiatedBy }){
        if(getters['isGroupModuleRegistered'](group_id)){
            if(getters['isGroupOpened'](group_id)) return

            if(initiatedBy == 'user'){
                commit('openWindow', group_id)
                return
            }

            if(rootState.auth.user.user_setting.open_all_chats_on_new_message)
                commit('openWindow', group_id)  
            
        } else {
            dispatch('getMissingGroup', group_id).then(() =>{
                if(rootState.auth.user.user_setting.open_all_chats_on_new_message) commit('openWindow', group_id)
            })
        }
    },

    getMissingGroup({ dispatch }, id){
        return axios.get('chat/group/' + id).then((res)=>{
            dispatch('setupGroupModule', res.data).then(()=>{

                dispatch('numGroupsWithUnseen').then(()=>{
                    dispatch('sortNewstGroups')
                })
            })
        }).catch((error) => { 
            // @todo Something triggered missing group, but request failed
        })
    },

    closeGroup({ commit, getters, dispatch }, group_id){
        if(getters['isGroupOpened'](group_id)) commit('closeWindow', group_id)

        dispatch(ns.groupModule(group_id, 'closed'), null, root)
        dispatch(ns.groupModule(group_id, 'participantsM/toggleTypingEventListeners'), false, root)
    },

    numGroupsWithUnseen({ state, commit, rootGetters }){
        let num = 0

        for(let i in state.groupsIds){
            let namespace = ns.groupModule(state.groupsIds[i], 'messagesM/seen') 

            if(!rootGetters[namespace]) num += 1 // if group is not seen, inc value   
        }

        commit('numGroupsWithUnseen', num)
    },

    // When user logs out, all chat related stored data must be purged
    purgeAllChatData({ state, commit }){
        // First remove group modules
        for(let i in state.groupsIds){
            let group_id = state.groupsIds[i]

            store.unregisterModule(ns.groupModule(group_id))
            Echo.leave(ev.groupChannel(group_id))
        }
        
        // then reset state
        commit('resetState')
    },

    removeGroupId({ commit }, group_id){
        commit('removeGroupId', group_id)
    },

    removeFilteredGroupsId({ commit }, id){
        commit('removeFilteredGroupsId', id)
    },

    registerChatListeners({dispatch, commit, store, rootState}){
        Echo.private(`App.Models.User.${rootState.auth.user.id}`)
            .listen('.group.new', e => {
                dispatch('setupGroupModule', e.data).then(() => {
                    dispatch('numGroupsWithUnseen')
                })
            })
    }

}

export default actions 