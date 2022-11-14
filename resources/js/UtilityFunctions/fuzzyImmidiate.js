import fuzzysort from 'fuzzysort'
import * as primitives from '@/UtilityFunctions/primitives.js'

/**
 * Based on @param properties searches collection for @search string
 * 
 * @param {String}       search     Query string
 * @param {Array|Object} collection Array of models or dictionary(object) where key is id of model and value is model 
 * @param {Array|String} properties Array or string of model properties
 * @return {Array}                  Models which match given @param search on models @param properties               
 */
 export function fuzzyImmidiate(search = '', collection = [], properties = []){
    if(! validateParams(search, collection, properties)) return collection

    return filterById(collection, goFuzzy(search, prepareCollectionForFuzzy(
        collection, 
        primitives.removeArrayDuplicates(primitives.arrOfStrings(
            typeof properties == 'string' ? [properties] : properties
        ))
    )))
}

function validateParams(search, collection, properties){
    if(!search) return false
    if(!collection) return false
    if(!properties) return false

    return true
}

function filterById(collection, ids){
    if(Array.isArray(collection))
        return collection.filter( ({ id }) => ids.includes(id) )
    if(typeof collection === 'object'){
        let col = []

        for(let i = 0; i < ids.length; i++){
            col.push(collection[ids[i]])
        }
        return col
    }

}

function goFuzzy(search, target){
    let results = fuzzysort.go(search, target, {
        key: 'search'
    }) 

    let ids = []

    for(let i = 0; i < results.length; i++){
        ids.push(results[i].obj.id)
    }

    return ids
}

function prepareCollectionForFuzzy(collection, properties){
    let target = []

    if(primitives.isArray(collection)){
        for(let i = 0; i < collection.length; i++){
            target.push({
                id: collection[i].id,
                search: primitives.mergeStrings(collection[i], properties),
            })
        }
    } else {
        for(let i in collection){
            target.push({
                id: collection[i].id,
                search: primitives.mergeStrings(collection[i], properties),
            })
        }
    }

    return target
}
