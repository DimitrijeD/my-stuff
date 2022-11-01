export function groupModule(id = '')
{
    let ns = 'group_module_'

    return `${ns}${id}`
}

export function groupsManager(action = null)
{
    let ns = 'groups_manager'

    return action 
        ? `${ns}/${action}`
        : ns
}

export function users(action = '')
{
    let ns = 'users'

    return action 
        ? `${ns}/${action}`
        : ns
}

export function chat_rules(action = null)
{
    let ns = 'chat_rules'

    return action 
        ? `${ns}/${action}`
        : ns
}

export function actionResponse(id = '', action = '')
{
    let ns = `actionResponse`
    if(!id) return ns

    if(!action) return `${ns}${id}`

    return `${ns}${id}/${action}`
}

export function actionResponseManager(action = '')
{
    let ns = 'action_response_manager'

    return action 
        ? `${ns}/${action}`
        : `${ns}`
}