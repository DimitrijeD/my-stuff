import * as h from '@/Store/functions/helpers.js'
import * as ev from '@/Store/modules/group_module/group_events_namespaces.js'
const root = {root:true}

const actions = {
    send({ commit, dispatch, rootState, state }){
        if((state.text === '' || state.text.trim() == '') && state.files.length == 0) return

        let message = new FormData();

        for( let i = 0; i < state.files.length; i++ ){
            message.append('files[' + i + ']', state.files[i]);
        }
        message.append('user_id', rootState.auth.user.id)
        message.append('group_id', state.group_id)
        message.append('text', state.text)

        // h.logMSitTookToExecStoreMsg()

        return new Promise((resolve, reject) => {
            axios.post('chat/message/store', message, {
                headers: {
                  "Content-Type": "multipart/form-data",
                },
            }).then(res => {

                commit('resetFiles')
                resolve(res)  
            }).catch( error => { 
                dispatch(ns.groupModule(state.group_id, 'makeActionResponseMessage'), {
                    ...error.response.data,
                    ...{
                        responseContext:{
                            moduleName: `groupId_${state.group_id}`,
                            important: true
                        }
                    } 
                }, root)
                
                reject(error)
            })
        })
    },

    addFile({ commit }, file){
        commit('addFile', file)
    },

    removeFile({ commit }, index){
        commit('removeFile', index)
    },

    text({ commit }, text){
        commit('text', text)
    },
}

export default actions 