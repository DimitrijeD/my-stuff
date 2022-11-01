import defaultState from './defaultState.js'

const mutations = {
    mergeUsers: (state, users) => state.users = { ...state.users, ...users},

    setFilterForAddUsers: (state, usersIds) => state.filterForAddUsers = usersIds,

    resetState: (state) => Object.assign(state, defaultState()),
    
}

export default mutations