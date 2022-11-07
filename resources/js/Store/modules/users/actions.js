import * as h from '@/Store/functions/helpers.js';

const actions = {
    searchForAddUsersInApi({ commit, dispatch, getters }, data){
        h.prepareUsersSearchRequest(data, getters.getAllUsersIds)

        return axios.post('users/search', h.prepareUsersSearchRequest(data, getters.getAllUsersIds)).then((res) => {
            if(!res.data.length) return

            commit('mergeUsers', h.createDict(res.data, 'id'))

            dispatch('searchForAddUsersInStore', {
                search_str: data.search_str,
                exclude: data.exclude
            })
        })
    },

    searchForAddUsersInStore({ state, commit }, data){
        commit('setFilterForAddUsers', h.excludeByIds( 
            h.findUsersByStr(state.users, data.search_str), data.exclude)
        )
    },

    resetState({ commit }){
        commit('resetState')
    },

    clearAddedUsersFromList({commit, state}, exclude){
        commit('setFilterForAddUsers', h.excludeByIds( 
            state.filterForAddUsers, 
            h.arrStringsToInt(exclude)) 
        )
    },

}

export default actions