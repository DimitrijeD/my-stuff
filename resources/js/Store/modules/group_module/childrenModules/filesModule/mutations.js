import defaultState from './defaultState.js'
import * as h from '@/Store/functions/helpers.js'

const mutations = {
    toggleOpened: (state) => state.opened = !state.opened,
    currentViewId: (state, id) => state.currentViewId = id,
    images: (state, array) => state.images = array,
    group_id: (state, group_id) => state.group_id = group_id,

    setImage(state, { blob, url }) {
        state.cache[url] = blob;
        state.evictionList.set(url, Date.now());

        if (state.size < state.maxSize) {
            state.size++;
        }
    },
}

export default mutations 