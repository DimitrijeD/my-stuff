export function getById(collection, id){
    if(!collection) return null

    for(let index in collection){
        if(collection[index].id == id) return collection[index]
    }

    return null
}

export function regExpressionMatch(find, text){
    let regex = new RegExp(find, 'i');
    return text.match(regex)
}

export function getModelsFromIds(collection, arrIds){
    if(!collection) return []
    var filteredModels = []

    for(let i in arrIds){
        for(let j in collection){
            if(collection[j].id == arrIds[i]) filteredModels.push( collection[j] )
        }
    }

    return filteredModels
}

export function nowISO(){
    return (new Date(Date.now())).toISOString();  
}

export function getMinObjKey(x){
    x = Object.keys(x)
    return Math.min(...x.filter(x => typeof x === 'string'))
}

export function getMinAndMax(array = []){
    if(array.length == 0) return []

    return [
        Math.min(...array.filter(x => typeof x === 'string')),
        Math.max(...array.filter(x => typeof x === 'string'))
    ]
}

export function getClosestMin(array = [], num){
    if(array.length == 0) return []

    return Math.max(...array.map(x => parseInt(x)).filter(x => x < num))
}

export function getAllIds(collection){
    var ids = []

    for(let i in collection){
        ids.push(collection[i].id)
    }

    return ids
}

export function createDict(collection, propertyName){
    let dict = {};

    for (let i in collection){
        if (collection[i]?.[propertyName]) {
            const model = collection[i];
            dict[model[propertyName]] = model;
        }
    }

    return dict;
}
  
export function prepareParticipantsForStoreRequest(data){
    let request = {
        group_id: data.group_id,
        usersToAdd: []
    }

    for(let i in data.addedUsersIds){
        request.usersToAdd.push({
            user_id: data.addedUsersIds[i],
            target_role: data.massAssignRolesTo
        })
    }

    return request
}

export function sortNewest(collection){
    return collection.sort(function(a,b){
        return new Date(b.updated_at) - new Date(a.updated_at);
    })
}

/**
 * From array of @param ids, return array which doesnt contain @param excludeIds
 * 
 * @param array ids 
 * @param array excludeIds 
 * @returns 
 */
export function excludeByIds(ids, excludeIds = []){
    excludeIds = arrStringsToInt(excludeIds)

    return ids.filter((el) => !excludeIds.includes(el))
}

export function prepareUsersSearchRequest(data, possesedIds){
    data.i_have_ids = possesedIds

    if('exclude' in data && data.exclude.length)
        data.i_have_ids = possesedIds.concat(data.exclude.filter((item) => possesedIds.indexOf(item) < 0)); // merge Ids without duplicates

    return data
}

export function arrStringsToInt(arr){
    return arr.map( (val) => {
        return typeof val == 'string' 
            ? parseInt(val)
            : val
    })
}

export function logMSitTookToExecStoreMsg(end = false){
    end
       ? console.log(Date.now() - localStorage.getItem('msgSent') )
       : localStorage.setItem('msgSent', Date.now())
}