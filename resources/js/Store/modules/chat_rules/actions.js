
const actions = 
{
    setupRules({commit}, chat_rules)
    {
        commit('rules', chat_rules.chat_rules)
        commit('keys', chat_rules.keys)
        commit('roles', chat_rules.roles)
        commit('groupTypes', chat_rules.groupTypes)
        commit('default_type', chat_rules.default_type)

        commit('init_num_messages', chat_rules.init_num_messages)
        commit('earliest_num_messages', chat_rules.earliest_num_messages)
    },

    resetState({ commit })
    {
        commit('resetState')
    }
}

export default actions