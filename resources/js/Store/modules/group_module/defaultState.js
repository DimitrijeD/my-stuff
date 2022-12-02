export default () => {
    return {
        id: null,
        model_type: null,
        name: null,
        created_at: null,
        updated_at: null,
        window: {
            minimized: false,
            showConfig: false,
            hasInitMessages: false,
            scrolledDownInitialy: false,
        },
        permissions: {
            add: [],
            change_group_name: null,
            change_group_type: null,
            change_role: {},
            remove: [],
            send_message: null,
        }
    }
}
