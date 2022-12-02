const actions = {
    storeUser(context, user){
        context.commit('setUser', user)
    }, 

    logout({commit, dispatch}){
        commit("logout")
        
        axios.get('logout').then((res)=>{
            dispatch( ns.actionResponseManager('provide'), { 
                ...res.data, 
                ...{ 
                    responseContext:{
                        moduleName: 'main',
                        important: false
                    }
                }
            } , {root:true} )

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
            dispatch(ns.chatRules('resetState'), null, {root:true})
        })
    },

    getUser({state, commit}){
        if(state.user) return

        return axios.get('user').then((res)=>{
            commit('setUser', res.data) 
        })
    },

    forgotPassword({dispatch}, form){
        axios.post('forgot-password', form).then((res) => {
            dispatch( ns.actionResponseManager('provide'), { 
                ...res.data, 
                ...{ 
                    responseContext:{
                        moduleName: 'main',
                        important: true
                    }
                }
            } , {root:true} )
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
                dispatch( ns.actionResponseManager('provide'), { 
                    ...res.data, 
                    ...{ 
                        responseContext:{
                            moduleName: 'main',
                            important: true
                        }
                    }
                } , {root:true} )
                resolve(res)  
            }).catch((error) =>{
                reject(error)
            })
        })
    },

    register({ dispatch,commit }, form){
        return new Promise((resolve, reject) => {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('register', form).then((res) => {
                    dispatch("storeUser", res.data.data.user).then(()=> {
                        localStorage.setItem("token", res.data.data.token)
                        dispatch( ns.actionResponseManager('provide'), { 
                            ...res.data, 
                            ...{ 
                                responseContext:{
                                    moduleName: 'main',
                                    important: true
                                }
                            }
                        } , {root:true} )

                        resolve(res)
                    })
                
                }).catch((error) =>{
                    reject(error)
                })
            })
        })
    },

    resendEmailVerification({ dispatch,commit }){
        return new Promise((resolve, reject) => {
            axios.post('email-verification/create-or-update', {}).then((res)=>{
                dispatch( ns.actionResponseManager('provide'), { 
                    ...res.data, 
                    ...{ 
                        responseContext:{
                            moduleName: 'main',
                            important: true
                        }
                    }
                } , {root:true} )

                resolve(res)
            }).catch((error) =>{
                reject(error)
            })
        })
    },

    checkIfValidated({ dispatch,commit }){
        return new Promise((resolve, reject) => {
            axios.get('email-verification/is-validated').then((res) => {
                dispatch( ns.actionResponseManager('provide'), { 
                    ...res.data, 
                    ...{ 
                        responseContext:{
                            moduleName: 'main',
                            important: true
                        }
                    }
                } , {root:true} )

                resolve(res)
            }).catch((error) => {
                reject(error)
            })
        })
    },

    verifyEmail({ dispatch,commit }, data){
        return new Promise((resolve, reject) => {
            axios.get(`email-verification/uid/${data.user_id}/c/${data.code}`).then( res => {
                commit('setUser', res.data.user) 
                dispatch( ns.actionResponseManager('provide'), { 
                    ...res.data, 
                    ...{ 
                        responseContext:{
                            moduleName: 'main',
                            important: true
                        }
                    }
                } , {root:true} )

                resolve(res)
            }).catch( error => {
                reject(error)
            })
        })
    },


    updateProfile({ dispatch,commit }, data){
        return new Promise((resolve, reject) => {
            axios.patch(`user/update`, data).then( res => {
                commit('setUser', res.data.data.user) 

                dispatch( ns.actionResponseManager('provide'), { 
                    ...res.data, 
                    ...{ 
                        responseContext:{
                            moduleName: 'main',
                            important: false
                        }
                    }
                } , {root:true} )

                resolve(res)
            }).catch( error => {
                dispatch(ns.actionResponse('main', 'inject'), {
                    messages: [error.message],
                    response_type: 'error'
                })

                reject(error)
            })
        })
    },

    deleteAccount({ dispatch,commit }){
        return new Promise((resolve, reject) => {
            axios.delete(`user/delete`).then( res => {
                commit("logout")
                localStorage.clear()
                localStorage.setItem('alreadyAttemptedGetUser', true)
                
                dispatch( ns.actionResponseManager('provide'), { 
                    ...res.data, 
                    ...{ 
                        responseContext:{
                            moduleName: 'main',
                            important: true
                        }
                    }
                } , {root:true} )

                resolve(res)
            }).catch( error => {
                dispatch(ns.actionResponse('main', 'inject'), {
                    messages: [error.message],
                    response_type: 'error'
                })

                reject(error)
            })
        })
    },

    login({ dispatch,commit }, form){
        return new Promise((resolve, reject) => {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('login', form).then((res) => {
                    localStorage.setItem("token", res.data.data.token)

                    dispatch("storeUser", res.data.data.user).then(()=> {
                        dispatch( ns.actionResponseManager('provide'), { 
                            ...res.data, 
                            ...{ 
                                responseContext:{
                                    moduleName: 'main',
                                    important: false
                                }
                            }
                        } , {root:true} )
                    })
                    resolve(res)
                }).catch((error) =>{
                    reject(error)
                })
            })
        })
    },

    getUserIfTokenExists({ dispatch,commit }, form){
        if(!localStorage.getItem("token")) return

        dispatch('getUser')
    },

}

export default actions