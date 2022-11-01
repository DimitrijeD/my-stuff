export function addedNewParticipants(data)
{
    if(data.addedUsers.length > 4){
        return {
            messages: [[ `${data.addedUsers.length} new users have been added to group.` ]],
            response_type: 'info'
        }
    }

    let usersNames = []
    
    for(let i in data.addedUsers){
        for(let j in data.participants){
            if(data.addedUsers[i].user_id == data.participants[j].id){
                usersNames.push(`${data.participants[j].first_name} ${data.participants[j].last_name}  `)
                break;
            }
        }   
    }

    return {
        messages: [usersNames, 'have been added to group.'],
        response_type: 'info'
    }

}