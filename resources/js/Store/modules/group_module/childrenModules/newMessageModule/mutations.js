import defaultState from './defaultState.js'
import * as h from '@/Store/functions/helpers.js'

const mutations = {
    group_id: (state, id) => state.group_id = id,
    text: (state, text) => state.text = text,

    addFile: (state, file) => state.files.push(file),
    removeFile: (state, index) => state.files.splice(index, 1),
    resetFiles: (state) => state.files = [],
}

export default mutations 