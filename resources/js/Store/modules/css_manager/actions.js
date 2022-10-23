import store from '@/Store/index.js';
import * as ns from '@/Store/module_namespaces.js'
import colors_module from '@/Store/modules/colors_module/colors_module.js'

const actions = 
{
    registerColorCalculator({ commit, dispatch })
    {
        let namespace = ns.colorsModule('color_calculator')
        store.registerModule(namespace, colors_module)
    },

}

export default actions