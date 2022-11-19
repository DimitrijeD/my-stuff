const root = {root:true}

export default {

    scrolledDownInitialy({ state, commit, getters, dispatch, rootState }, bool){
        commit('scrolledDownInitialy', bool)
    },

    toggleWindow({commit}){
        commit('toggleWindow')
    },

    toggleConfig({commit}){
        commit('toggleConfig')
    },

    /**
     * Action responsible for providing ActionResponse card with message content only if chat is opened (not minimized)
     */
    makeActionResponseMessage({ state, dispatch }, data){
        if(!state.window.minimized){
            dispatch( ns.actionResponseManager('provide'), data, root )
        } 
    },

    closed({ state, commit }){
        commit('minimized', false)
        commit('showConfig', false)
        commit('scrolledDownInitialy', true) // after closing group and oppening it again, scroll will go to last message
    },
}