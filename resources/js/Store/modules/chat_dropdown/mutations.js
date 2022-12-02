import defaultState from "./defaultState"

export default {
    toggle: (state) => state.show = !state.show,
    resetState: (state) => state = defaultState,
}