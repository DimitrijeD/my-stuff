const mutations = {
    messages: (state, messages) => state.messages = messages,
    response_type: (state, response_type) => state.response_type = response_type,
    id: (state, val) => state.id += 1,
}

export default mutations