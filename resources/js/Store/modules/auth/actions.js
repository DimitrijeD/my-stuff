import * as ns from '@/Store/module_namespaces.js'

const actions = {
    storeUser: (context, user) => {
        context.commit('setUser', user)
    }, 

    logout(context) 
    {
        context.commit("logout")
        localStorage.clear();
        
        axios.get('logout').then(()=>{
            context.commit("logout")
        });
        
        context.dispatch(ns.groupsManager() + '/purgeAllChatData', null, {root:true}).then(() => {
            context.dispatch(ns.users()      + '/resetState', null, {root:true})
            context.dispatch(ns.chat_rules() + '/resetState', null, {root:true})
        })
    },

    getUser(context) 
    {
        if(context.state.user) return

        return axios.get('user').then((res)=>{
            context.dispatch('storeUser', res.data)
        })
    },

}

export default actions