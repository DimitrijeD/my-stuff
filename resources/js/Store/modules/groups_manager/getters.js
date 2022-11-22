import * as h from '@/Store/functions/helpers.js'
import store from '@/Store/index.js'

const getters = {
    groupsIds: (state) => state.groupsIds,

    openedGroupsIds: (state) => state.openedGroupsIds,

    filteredGroupsIds: (state) => state.filteredGroupsIds,

    isGroupModuleRegistered: (state) => (group_id) => store.hasModule(ns.groupModule(group_id)),
    
    numGroupsWithUnseen: (state) => state.numGroupsWithUnseen,

    isGroupOpened: (state) => (group_id) => state.openedGroupsIds.includes(group_id),

    getAllGroups: (state) => {
        let groups = []

        state.groupsIds.forEach(id => groups.push( store.getters[ns.groupModule(id, 'state')] ) )

        return groups
    },

    getGroupById: (state) => (id) => store.getters[ns.groupModule(id, 'state')],

    getGroupsById: (state) => (ids) => {
        let groups = []

        ids.forEach(id => groups.push( store.getters[ns.groupModule(id, 'state')]) )

        return groups
    },

    dropdown: (state) => state.dropdown,
}

export default getters 