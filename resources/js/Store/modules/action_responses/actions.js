const actions = {

    die({commit}){
        commit('messages', [])
        commit('response_type', '')
    },

    inject({commit}, data){
        if(data?.messages && data?.response_type){
            commit('messages', data.messages)
            commit('response_type', data.response_type)
            commit('id')
        } else {
            // @todo 
            console.log('ActionResponse Module: invalid object, cannot show info', data)
        }

    },

    flush({commit}, data){
        commit('messages', [])
        commit('response_type', '')
    }
}

export default actions