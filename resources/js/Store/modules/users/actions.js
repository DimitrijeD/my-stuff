import * as h from '@/Store/functions/helpers.js';
import * as ns from '@/Store/module_namespaces.js'

const actions = {
    searchForAddUsersInApi({ commit, dispatch, getters }, data){
        const responseContext = {responseContext: data.responseContext }
        delete data.responseContext

        return new Promise((resolve, reject) => {
            axios.post('users/search', h.prepareUsersSearchRequest(data, getters.getAllUsersIds)).then((res) => {
                if(!res.data.length) 
                    resolve(res)

                commit('mergeUsers', h.createDict(res.data, 'id'))

                dispatch('searchForAddUsersInStore', {
                    search_str: data.search_str,
                    exclude: data.exclude
                })

                resolve(res)
            }).catch((error) =>{
                dispatch( ns.actionResponseManager('provide'), { 
                    ...error.response.data, 
                    ...responseContext
                } , {root:true} )
                reject(error)
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