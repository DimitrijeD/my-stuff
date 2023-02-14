import * as h from '@/Store/functions/helpers.js';

const mutations = 
{
    id:         (state, int) => state.id = int,
    model_type: (state, string) => state.model_type = string,
    name:       (state, string) => state.name = string,
    created_at: (state, val) => state.created_at = val,
    updated_at: (state, date) => state.updated_at = date,

    toggleWindow: (state) => state.window.minimized  = !state.window.minimized,
    toggleConfig: (state) => state.window.showConfig = !state.window.showConfig,
    toggleFiles: (state) => state.window.showFiles = !state.window.showFiles,
    
    hasInitMessages: (state, bool) => state.window.hasInitMessages = bool,
    scrolledDownInitialy: (state, bool) => state.window.scrolledDownInitialy = bool,
    showConfig: (state, bool) => state.window.showConfig = bool,
    minimized: (state, bool) => state.window.minimized = bool,

    permissions: (state, permissions) => state.permissions = permissions,

}

export default mutations 