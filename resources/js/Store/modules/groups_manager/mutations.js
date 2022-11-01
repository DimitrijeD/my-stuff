import defaultState from './defaultState.js'

const mutations = {
    setFilteredGroupsIds: (state, ids) => state.filteredGroupsIds = ids,
    addToFilteredGroups: (state, id) => state.filteredGroupsIds.push(id),
    removeFilteredGroupsId: (state, id) => state.filteredGroupsIds = state.filteredGroupsIds.filter((item) => item !== id ),

    addGroupsIds: (state, groupsIds) => state.groupsIds = state.groupsIds.concat(groupsIds),

    removeGroupId: (state, group_id) => state.groupsIds.splice(state.groupsIds.indexOf(group_id), 1),

    openWindow: (state, group_id) => state.openedGroupsIds.push(group_id),

    closeWindow: (state, group_id) => {
        const index = state.openedGroupsIds.indexOf(group_id)
        if(index > -1){
            state.openedGroupsIds.splice(index, 1)
        } 
    },

    numGroupsWithUnseen: (state, int) => state.numGroupsWithUnseen = int,

    resetState: (state) => Object.assign(state, defaultState()),
}

export default mutations 