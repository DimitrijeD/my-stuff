import * as h from '@/Store/functions/helpers.js'

const getters = {
    group_id:         (state) => state.group_id, 
    opened:           (state) => state.opened,
    currentViewId:    (state) => state.currentViewId,
    images:           (state) => state.images,
    numberOfPreviews: (state) => state.numberOfPreviews,
    minimumPreviews:  (state) => state.minimumPreviews,

    currentView: (state) => state.images.find(image => image.id == state.currentViewId),

    allURLsFromImages: (state) => {
        let urls = []
        for(let i in state.images){
            urls.push(state.images[i].url)
        }
        return urls
    },

    blob: (state) => (url) => {
        return state.cache[url]
            ? state.cache[url]
            : '';
        // return state.cache[url]
        //     ? URL.createObjectURL(new Blob([state.cache[url]]))
        //     : '';
    },

}

export default getters 