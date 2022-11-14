export function mergeStrings(obj, props){
    let str = ''

    for(let i = 0; i < props.length; i++){
        str += `${obj[props[i]]}`
    }

    return str
}

export function arrOfStrings(arr){
    for(let i = 0; i < arr.length; i++){
        arr[i] = String(arr[i])
    }

    return arr
}

export function removeArrayDuplicates(arr){
    return _.uniq(arr)
}

export function isArray(a){
    return (!!a) && (a.constructor === Array) 
}

export function isObject(o){
    return (!!o) && (o.constructor === Object)
}