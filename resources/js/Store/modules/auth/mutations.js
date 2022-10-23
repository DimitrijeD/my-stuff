const mutations = {
    setUser: (state, user) => state.user = user,

    logout: (state) => state.user = null,

    setToken: (state, token) => state.token = token,
}

export default mutations