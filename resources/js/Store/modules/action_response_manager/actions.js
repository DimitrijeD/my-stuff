import store from '@/Store/index.js';
import action_responses from '@/Store/modules/action_responses/action_responses.js';

const actions = {
    /**
     * This action registers main ActionResponse module
     */
    initModule({context}, id){
        if( !store.hasModule(ns.actionResponse(id)) ){
            store.registerModule(ns.actionResponse(id), action_responses)
        }
    },

    unregisterModule({context}, id){
        if( store.hasModule(ns.actionResponse(id)) ){
            store.unregisterModule(ns.actionResponse(id))
        }
    },

    provide({state, dispatch, rootState}, data){
        let shouldDispatch = false

        if(data.responseContext.important){
            shouldDispatch = true
        } else if( rootState.auth.user?.user_setting.show_only_important_notifications ) {
            shouldDispatch = false
        }
        
        if(shouldDispatch){
            dispatch(ns.actionResponse(data.responseContext.moduleName, 'inject'), data, {root:true})
        }
    }

}

export default actions