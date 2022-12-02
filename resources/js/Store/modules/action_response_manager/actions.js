import store from '@/Store/index.js';
import action_responses from '@/Store/modules/action_responses/action_responses.js';

const actions = {
    initModule({context}, id){
        if( !store.hasModule(ns.actionResponse(id)) )
            store.registerModule(ns.actionResponse(id), action_responses)
    },

    unregisterModule({context}, id){
        if( store.hasModule(ns.actionResponse(id)) )
            store.unregisterModule(ns.actionResponse(id))
    },

    provide({state, dispatch, rootState}, data){
        let shouldDispatch = rootState.auth.user?.user_setting.show_only_important_notifications 
            ? false 
            : true
 
        if(data.responseContext.important)
            shouldDispatch = true
        
        if(shouldDispatch){
            if(store.hasModule(ns.actionResponse(data.responseContext.moduleName))){
                dispatch(ns.actionResponse(data.responseContext.moduleName, 'inject'), data, {root:true})
            } else {
                // @todo store this message in database and once user opens group, he should get this message
            }
        }
    },

}

export default actions