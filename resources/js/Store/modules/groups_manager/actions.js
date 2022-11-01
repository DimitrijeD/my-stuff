import * as h from '@/Store/functions/helpers.js';
import * as ns from '@/Store/module_namespaces.js'
import store from '@/Store/index.js';
import group_module from '@/Store/modules/group_module/group_module.js';

const root = {root:true}

const actions = {
    /**
     * Entry point for chat data.
     * Includes: all bussiness logic and all users chat groups
     */
    init({ dispatch }){
        axios.get('chat/init').then((res)=>{
            for(let i in res.data.groups){
                dispatch('initGroup', res.data.groups[i])
            }

            dispatch(ns.chat_rules('setupRules'), res.data.chat_rules, root).then(() => {
                dispatch('numGroupsWithUnseen')
            })
        }).catch(error => {
            // Fatal error, request reload because without this successful request, chatting will not work
        })
    },

    filterGroupsBySearchString({ commit, getters }, str){
        commit('setFilteredGroupsIds', h.getAllIds(h.sortNewest( getters['getGroupsById'](h.getBySearchString(getters['getAllGroups'], str)) )))
    },

    sortNewstGroups({ commit, getters }){
        commit('setFilteredGroupsIds', h.getAllIds(h.sortNewest(getters['getGroupsById'](getters['filteredGroupsIds']))))
    },

    initGroup({ commit, dispatch }, group){
        let namespace = ns.groupModule(group.id)
        store.registerModule(namespace, group_module)

        return dispatch(namespace + '/initGroup', group, root).then(() => {
            dispatch(namespace + '/listenForNewMessages', group.id , root)
            commit('addGroupsIds', [group.id])
            commit('addToFilteredGroups', group.id)
        })
    },

    storeGroup({ commit, dispatch }, data){
        axios.post('chat/group/store', data)
        .then( res => {
            dispatch('initGroup', res.data).then(() => {
                dispatch('sortNewstGroups')
                commit('openWindow', res.data.id)
                commit(ns.groupModule(res.data.id) + '/gotEarliestMsg', true, root)
            })
        })
    },

    openGroup({ commit, dispatch, getters }, id){
        if(getters['isGroupModuleRegistered'](id)){
            if(!getters['isGroupOpened'](id)) commit('openWindow', id)  
        } else {
            dispatch('getMissingGroup', id).then(() =>{
                commit('openWindow', id)
            })
        }
    },

    getMissingGroup({ dispatch }, id){
        return axios.get('chat/group/' + id).then((res)=>{
            dispatch('initGroup', res.data).then(()=>{
                dispatch('numGroupsWithUnseen').then(()=>{
                    dispatch('sortNewstGroups')
                })
            })
        }).catch((error) => { 

        })
    },

    closeGroup({ commit, getters, dispatch }, group_id){
        if(getters['isGroupOpened'](group_id)) commit('closeWindow', group_id)
        dispatch(ns.groupModule(group_id) + '/scrolledDownInitialy', true, {root:true})
        // * Disconnect irelevant listeners here
    },

    numGroupsWithUnseen({ state, commit, rootGetters }){
        let num = 0
        // let namespace = ''
        const seenGetter = '/seen'
        
        for(let i in state.groupsIds){
            let namespace = ns.groupModule(state.groupsIds[i]) 

            if(store.hasModule(namespace)){
                if(!rootGetters[namespace + seenGetter]) num += 1 // if group is not seen, inc value   
            } else {
                console.log(`group_id = ${state.groupsIds[i]} is inside 'store.groups.groupsIds' but module doesn't exist. FATAL`)
                console.log('solution flow, do get groupById from api, then if ok, init that group, else, remove it from store')
            }
        }

        commit('numGroupsWithUnseen', num)
        return 
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