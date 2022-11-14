import fuzzysort from 'fuzzysort'
import * as primitives from '@/UtilityFunctions/primitives.js'

export function fuzzyDeep(search = '', collection = [], tree = {}){
    if(! validateParams(search, collection, tree)) return collection

    let preparedCollection = prepareCollectionForSearch(collection, tree)

    return goFuzzyDeep(search, preparedCollection, collection)
}

function validateParams(search, collection, properties){
    if(!search) return false
    if(!collection) return false
    if(!properties) return false

    return true
}

function prepareCollectionForSearch(collection, nestedProperties){
    let searchStrings = []

    for(let i = 0; i < collection.length; i++){
        searchStrings.push({
            search: buildStringOfModel(collection[i], nestedProperties),
            model_id: collection[i].id
        })
    }

    return searchStrings
}

function buildStringOfModel(model, nestedProperties){
    let fullStr = ''

    for(let i = 0; i < nestedProperties.length; i++){
        let path = nestedProperties[i].path
        let props = nestedProperties[i].props

        if(path){
            let nestedModel = _.get(model, path)

            if( primitives.isArray(nestedModel)){
                fullStr += delimiter() + makeStringFromCollection(nestedModel, props)
            } else if(primitives.isObject(nestedModel)) {
                fullStr += delimiter() + makeStringFromDictionary(nestedModel, props)
            } else {
                // ??
                console.log('not obj, and not array...')
            }

        } else {
            fullStr += delimiter() + primitives.mergeStrings(model, props)
        }
    }

    return fullStr
}

function delimiter(){
    return '|'
}


function makeStringFromCollection(collection, props){
    let str = ''
    for(let i = 0; i < collection.length; i++){
        str += delimiter() + primitives.mergeStrings(collection[i], props)
    }
    return str
}

function makeStringFromDictionary(dict, props){
    let str = ''
    for(let o in dict){
        str += delimiter() + primitives.mergeStrings(dict[o], props)
    }
    return str
}

function goFuzzyDeep(search, target, collection){
    let results = fuzzysort.go(search, target, {
        key: 'search'
    }) 

    let filteredCollection = []
    // ok so do i return array or dictionary
    for(let i = 0; i < results.length; i++){
        for(let j = 0; j < collection.length; j++){
            if(collection[j].id == results[i].obj.model_id)
            filteredCollection.push(collection[j])
        }
    }

    return filteredCollection
}
