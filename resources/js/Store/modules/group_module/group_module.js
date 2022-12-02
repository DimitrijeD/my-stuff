import state from './state.js'
import getters from './getters.js'
import actions from './actions.js'
import mutations from './mutations.js'
import modules from './nestedModules.js'

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
    modules,
}