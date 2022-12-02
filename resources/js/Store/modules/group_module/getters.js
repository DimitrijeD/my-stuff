import * as h from '@/Store/functions/helpers.js'

const getters = 
{
    state: (state) => state,

    id: (state) => state.id,

    model_type: (state) => state.model_type,

    name: (state) => state.name,

    created_at: (state) => state.created_at,

    updated_at: (state) => state.updated_at,

    /**
     * Is chat window minimized or not
     */
    minimized: (state) => state.window.minimized,

    /**
     * Is chat window config opened
     */
    showConfig: (state) => state.window.showConfig,

    window: (state) => state.window,

    permissions: (state) => state.permissions,
    canSendMessage: (state) => state.permissions.send_message,
    canChangeName: (state) => state.permissions.change_group_name,
    canChangeGroupType: (state) => state.permissions.change_group_type,
}

export default getters 