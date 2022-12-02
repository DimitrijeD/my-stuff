const getters = 
{
    StateRules:      (state) => state.rules,
    StateKeys:       (state) => state.keys,
    StateRoles:      (state) => state.roles,
    StateGroupTypes: (state) => state.groupTypes,

    rules:      (state) => state.rules,
    keys:       (state) => state.keys,
    roles:      (state) => state.roles,
    groupTypes: (state) => state.groupTypes,
    default_type: (state) => state.default_type,

    init_num_messages:     (state) => state.init_num_messages,
    earliest_num_messages: (state) => state.earliest_num_messages,
}

export default getters