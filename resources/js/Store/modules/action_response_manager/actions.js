import store from '@/Store/index.js';
import action_responses from '@/Store/modules/action_responses/action_responses.js';
import * as ns from '@/Store/module_namespaces.js';

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

}

export default actions