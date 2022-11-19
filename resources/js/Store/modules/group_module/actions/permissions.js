import {Permissions} from '@/Components/Chat/policies/Permissions.js'
import * as actionMessageFormater from  '@/Store/modules/group_module/actionMessageFormater.js'

export default {

    createPermissions({commit, state, rootState, getters}){
        commit('permissions', (new Permissions(
            rootState[ns.chat_rules()].keys, 
            rootState[ns.chat_rules()].rules, 
            getters.getUserRole(rootState.auth.user.id), 
            state.model_type
        )).create() )
    },

    changeGroupType({ state, dispatch }, payload){
        if(!payload.group_id || !payload.model_type) return       

        return new Promise((resolve, reject) => {
            axios.post("chat/group/change-group-type", payload).then((res) => {
                dispatch('makeActionResponseMessage', {
                    ...res.data,
                    ...{
                        responseContext:{
                            moduleName: `config.groupId_${state.id}`,
                            important: true
                        }
                    } 
                }) 

                resolve(res)  
            })
        }).catch( error => { 
            dispatch('makeActionResponseMessage', {
                ...error.response.data,
                ...{
                    responseContext:{
                        moduleName: `groupId_${state.id}`,
                        important: true
                    }
                } 
            })
            
            reject(error)
        })
    },

}