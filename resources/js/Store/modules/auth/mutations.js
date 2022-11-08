const mutations = {
    setUser(state, user) {
        state.user = user

        if(user?.user_setting?.colorz){
            state.user.user_setting.colorz = JSON.parse(user.user_setting.colorz)
        }
    },
    logout: (state) => state.user = null,
}

export default mutations