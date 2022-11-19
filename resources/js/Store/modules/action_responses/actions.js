const actions = {

    // For test only, can be deleted
    test({commit, state}){
        commit('messages', [{
                key: randomString(Math.floor(Math.random() * 10)),
            },
        ])

        let response_type = ''

        if(state.id%1 == 0) response_type = 'error'
        if(state.id%2 == 0) response_type = 'success' 
        if(state.id%3 == 0) response_type = 'info' 
        if(state.id%4 == 0) response_type = 'unknown' 

        commit('response_type', response_type)
        commit('id')
    },
    // --------------------------------

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
            console.log('ActionResponse Module: invalid object, cannot show info', data)
        }

    },

    flush({commit}, data){
        commit('messages', [])
        commit('response_type', '')
    }
}

// For test only, can be deleted
function randomChar() {
    var index = Math.floor(Math.random() * 62);
    // Generate Number character
    if (index < 10) {
        return String(index);
        // Generate capital letters
    } else if (index < 36) {
        return String.fromCharCode(index + 55);
    } else {
        // Generate small-case letters
        return String.fromCharCode(index + 61);
    }
}

function randomString(length) {
    var result = "";
    while (length > 0) {
        result += randomChar();
        length--;
    }

    return result;
}
// --------------------------------

export default actions