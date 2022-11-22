import * as h from '@/Store/functions/helpers.js'
import * as collection from '@/UtilityFunctions/collection.js'
import { fuzzyDeep } from '@/UtilityFunctions/fuzzyDeep.js'

import store from '@/Store/index.js'
import group_module from '@/Store/modules/group_module/group_module.js'

const root = {root:true}

const actions = {
    /**
     * Entry point for chat data.
     */
     init({ dispatch }){
        return axios.get('chat/init').then((res)=>{
            dispatch(ns.chat_rules('setupRules'), res.data.chat_rules, root).then(() => {

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

    /**
     * Both search approaches work, but should only use one, duh
     */
    filterGroupsBySearchString({ commit, getters }, str){
        commit('setFilteredGroupsIds', h.getAllIds( 
            fuzzyDeep(str, getters['getAllGroups'], [
                {
                    path: '',
                    props: ['name']
                },
                {
                    path: 'participants',
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

        dispatch(ns.groupModule(group.id, 'initGroup'), group, root)
    },

    storeGroup({ commit, dispatch }, data){
        return new Promise((resolve, reject) => {
            axios.post('chat/group/store', data).then( res => {

                dispatch('setupGroupModule', res.data).then(() => {
                    dispatch('sortNewstGroups')
                    commit('openWindow', res.data.id)
                    commit(ns.groupModule(res.data.id, 'gotEarliestMsg'), true, root)
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
            if(getters['isGroupOpened'](group_id)){
                return
            }

            if(initiatedBy == 'user'){
                commit('openWindow', group_id)
                return
            }

            if(rootState.auth.user.user_setting.open_all_chats_on_new_message) {
                commit('openWindow', group_id)  
            }
        } else {
            dispatch('getMissingGroup', group_id).then(() =>{
                if(rootState.auth.user.user_setting.open_all_chats_on_new_message){
                    commit('openWindow', group_id)
                }
            })
        }
    },

    getMissingGroup({ dispatch }, id){
        axios.get('chat/group/' + id).then((res)=>{
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
        // dispatch(ns.groupModule(group_id, 'scrolledDownInitialy'), true, root)
        dispatch(ns.groupModule(group_id, 'closed'), null, root)
        // * Disconnect irelevant listeners here
    },

    numGroupsWithUnseen({ state, commit, rootGetters }){
        let num = 0
        const seenGetter = '/seen'

        for(let i in state.groupsIds){
            let namespace = ns.groupModule(state.groupsIds[i]) 

            if(store.hasModule(namespace)){
                if(!rootGetters[namespace + seenGetter]) num += 1 // if group is not seen, inc value   
            } else {
                // @todo group with `id` is inside 'store.groups.groupsIds' but module doesn't exist. FATAL`
                // 'solution flow, do get groupById from api, then if ok, init that group, else, remove it from store'
                return
            }
        }

        commit('numGroupsWithUnseen', num)
    },

    // When user logs out, all chat related stored data must be purged
    purgeAllChatData({ state, commit }){
        // First remove group modules
        for(let i in state.groupsIds){
            let group_id = state.groupsIds[i]

            store.unregisterModule(ns.groupModule(group_id))
            Echo.leave('group.' + group_id)
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

}

export default actions 