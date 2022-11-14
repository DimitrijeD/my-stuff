export function sortParticipantsByRoleHierarchy(participants, roles = []){
    if(roles.length == 0) return participants

    let groupedByRole = {}
    let sortedByRole = []

    for(let i in roles){
        groupedByRole[roles[i]] = []
    }

    for(let i in participants){
        groupedByRole[participants[i].pivot.participant_role].push(participants[i]) 
    }

    for(let i in groupedByRole){
        sortedByRole = sortedByRole.concat(groupedByRole[i]);
    }

    return sortedByRole
}

