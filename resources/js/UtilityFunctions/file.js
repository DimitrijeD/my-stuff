import FileConstants from '@/Constants/FileConstants.js'

export function isImage(url){
    let split = url.split('/')
    let name = split[split.length - 1]

    let temp = name.split('.')

    let extension = temp[1]
    
    return FileConstants.previewableImageExtensions.includes(extension.toLowerCase())
}

export function getSrc(file){
    return URL.createObjectURL(file)
}

export function isImageByMime(type){
    let split = type.split('/')

    return split[0].toLowerCase() == 'image'
}

export function fileNameFromUrl(url){
    if(!url) return ''

    let split = url.split('/')

    if(split.length - 1 < 0) return ''
    
    return split[split.length - 1]
}