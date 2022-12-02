export function groupModule(id = '', action = '')
{
    let ns = 'groupModule_'

    if(!id) return ns

    if(!action) return `${ns}${id}`

    return `${ns}${id}/${action}`
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

export function chatRules(action = null)
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

    if(!action) return `${ns}_${id}`

    return `${ns}_${id}/${action}`
}

export function actionResponseGroup(id = '')
{
    let prefix = `groupId_`
    if(!id) return prefix

    return `${prefix}_${id}`
}

export function actionResponseManager(action = '')
{
    let ns = 'action_response_manager'

    return action 
        ? `${ns}/${action}`
        : `${ns}`
}

export function chatDropdown(action = ''){
    let ns = 'chat_dropdown'
    if(!action) return ns

    return `${ns}/${action}`
}