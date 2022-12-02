import defaultState from "./defaultState"

const mutations = 
{
    rules: (state, array) => state.rules = array,
    keys: (state, array) => state.keys = array,
    roles: (state, array) => state.roles = array,
    groupTypes: (state, array) => state.groupTypes = array,
    default_type: (state, str) => state.default_type = str,

    init_num_messages: (state, int) => state.init_num_messages = int,
    earliest_num_messages: (state, int) => state.earliest_num_messages = int,

    resetState: (state) => Object.assign(state, defaultState()),
}

export default mutations