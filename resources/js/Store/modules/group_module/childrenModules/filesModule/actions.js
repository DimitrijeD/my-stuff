import * as FileUtilFuncs from '@/UtilityFunctions/file.js';
import JSZip from 'jszip';
const root = {root:true};

const actions = {
    toggleOpened({state, commit}){
        commit('toggleOpened');
    },

    focusImage({ commit, getters, dispatch}, { id, message_id, openIfClosed }){
        if(openIfClosed) commit('toggleOpened');

        commit('currentViewId', id);

        dispatch('collectAllImageUrlsFromMesages')
    },

    collectAllImageUrlsFromMesages({state, commit, rootGetters}){
        let messages = rootGetters[ns.groupModule(state.group_id, 'messagesM/messages')];

        let images = [];

        for(let id in messages){
            if(messages[id]?.files ?? null){
                for(let index in messages[id].files){
                    if(FileUtilFuncs.isImage(messages[id].files[index].url)) 
                    images.push(messages[id].files[index]);
                }
            }
        }

        commit('images', images)
    },

    currentViewId({ commit }, id){
        commit('currentViewId', id);
    },

    getImage({ state, commit }, url) {
        if (state.cache[url]) {
            state.evictionList.delete(url);
            state.evictionList.set(url, Date.now());
            return Promise.resolve(state.cache[url]);
        } else {
            return new Promise((resolve, reject) => {
                axios.get(url, { 
                    responseType: 'arraybuffer',
                    params: { group_id: state.group_id }
                }).then(res => {
                    console.log([...state.evictionList.entries()]);
                    if (state.size === state.maxSize) {
                        // Evict the least recently used item
                        const oldestKey = [...state.evictionList.entries()].sort((a, b) => a[1] - b[1])[0][0];
                        state.evictionList.delete(oldestKey);
                        delete state.cache[oldestKey];
                    }
                    
                    commit('setImage', { 
                        url: url, 
                        blob: URL.createObjectURL(new Blob([res.data])) 
                    });

                    resolve(res);
                }).catch( error => { 

                    reject(error);
                });
            });
        }
    },

    getManyImages({ state, commit }, urls) {
        return new Promise((resolve, reject) => {
            axios.post('/c/images', { 
                group_id: state.group_id,
                urls: urls,
            }, {
                responseType: 'blob', 
            }).then(res => {

                JSZip.loadAsync(res.data).then(zip => {
                    var images = [];
                    let promises = [];
                    Object.values(zip.files).forEach((zipEntry) => {
                        promises.push(zipEntry.async("blob").then( blob => {
                            var url = URL.createObjectURL(blob);
                            var image = new Image();
                            image.src = url;
                            images.push({name: zipEntry.name, url: image.src});
                        }));
                    });
                
                    Promise.all(promises).then(() => {
                        console.log(images)
                        // commit('setImages', images);
                    });
                });

                resolve(res);
            }).catch( error => { 

                reject(error);
            });
        });

    },
}

export default actions 