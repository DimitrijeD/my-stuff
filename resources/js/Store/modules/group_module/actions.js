import messages from './actions/messages.js'
import participants from './actions/participants.js'
import window from './actions/window.js'
import permissions from './actions/permissions.js'
import group_stuff from './actions/group_stuff.js'

export default {
    ...messages,
    ...participants,
    ...window,
    ...permissions,
    ...group_stuff,
}