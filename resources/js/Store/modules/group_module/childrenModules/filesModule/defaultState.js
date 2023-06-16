export default () => { 
    return {
        group_id: null,
        opened: false,
        currentViewId: '',
        images: [],
        numberOfPreviews: 3,
        imagesIds: {},
        minimumPreviews: 10,

        cache: {},
        evictionList: new Map(),
        maxSize: 100,
        size: 0,
    }
}

/**
 * @opened - bool, is files window opened
 * @currentViewId - int, file id which is currently being displayed
 * @images - array, of all image URL-s relative to current
 * @numberOfPreviews - const int, how many urls to maintain in @images array inorder to always have some url to show
 * @imagesIds - @todo dictionary, keys are messages ids, value is array of image id that belong to that message
 * 
 * @cache
 * @evictionList
 * @maxSize
 * @size
 */