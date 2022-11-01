import * as ns from '@/Store/module_namespaces.js'

const actions = {
    storeUser: (context, user) => {
        context.commit('setUser', user)
    }, 

    logout({commit, dispatch}) 
    {
        commit("logout")
        
        axios.get('logout').then((res)=>{
            dispatch(ns.actionResponse('main', 'inject'), res.data, {root:true})

            const theme = localStorage.getItem('vueuse-color-scheme')
            localStorage.clear()
            localStorage.removeItem('token');
            localStorage.setItem('vueuse-color-scheme', theme)
            localStorage.setItem('alreadyAttemptedGetUser', true)
        }).catch((e) => {
            localStorage.clear()
        })
        
        dispatch(ns.groupsManager('purgeAllChatData'), null, {root:true}).then(() => {
            dispatch(ns.users('resetState'), null, {root:true})
            dispatch(ns.chat_rules('resetState'), null, {root:true})
        })
    },

    getUser({state, dispatch}) 
    {
        if(state.user) return

        return axios.get('user').then((res)=>{
            dispatch('storeUser', res.data)
        })
    },

    forgotPassword({dispatch}, form){
        axios.post('forgot-password', form).then((res) => {
            dispatch(ns.actionResponse('main', 'inject'), res.data)
        }).catch((error) =>{
            dispatch(ns.actionResponse('main', 'inject'), {
                messages: [error.message],
                response_type: 'error'
            })
        })
    },

    resetRassword({ dispatch }, form){
        return new Promise((resolve, reject) => {
            axios.post('reset-password', form).then(res => {
                dispatch(ns.actionResponse('main', 'inject'), res.data)
                resolve(res)  
            }, error => {
                reject(error)
            })
        })
    },

    register({ dispatch,commit }, form){
        return new Promise((resolve, reject) => {
            axios.post('register', form).then((res) => {
                dispatch("storeUser", res.data.data.user).then(()=> {
                    localStorage.setItem("token", res.data.data.token)
                    dispatch(ns.actionResponse('main', 'inject'), res.data)

                    resolve(res)
                })
               
            }).catch((error) =>{
                reject(error)
            })
        })
    },

    resendEmailVerification({ dispatch,commit }){
        return new Promise((resolve, reject) => {
            axios.post('email-verification/create-or-update', {}).then((res)=>{
                dispatch(ns.actionResponse('main', 'inject'), res.data)

                resolve(res)
            }).catch((error) =>{
                reject(error)
            })
        })
    },

    checkIfValidated({ dispatch,commit }){
        return new Promise((resolve, reject) => {
            axios.get('email-verification/is-validated').then((res) => {
                dispatch(ns.actionResponse('main', 'inject'), res.data)

                resolve(res)
            }).catch((error) => {
                reject(error)
            })
        })
    },

    verifyEmail({ dispatch,commit }, data){
        return new Promise((resolve, reject) => {
            axios.get(`email-verification/uid/${data.user_id}/c/${data.code}`).then( res => {
                
                dispatch('storeUser', res.data.user)
                dispatch(ns.actionResponse('main', 'inject'), res.data)

                resolve(res)
            }).catch( error => {
                reject(error)
            })
        })
    },

}

export default actions