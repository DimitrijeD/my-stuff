import * as ns from '@/Store/module_namespaces.js'
const root = {root:true}
import store from '@/Store/index.js';
import action_responses from '@/Store/modules/action_responses/action_responses.js';

const actions = {
    /**
     * This action registers main ActionResponse module
     */
    initModule({context}, id){
        store.registerModule(ns.actionResponse(id), action_responses)
    },

    unregisterModule({context}, id){
        store.unregisterModule(ns.actionResponse(id))
    },

}

export default actions