import window from './actions/window.js'
import permissions from './actions/permissions.js'
import group_stuff from './actions/group_stuff.js'

export default {
    ...window,
    ...permissions,
    ...group_stuff,
}