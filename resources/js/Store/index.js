import { createStore } from 'vuex'
import auth from "@/Store/modules/auth/auth.js"
import groups_manager from "@/Store/modules/groups_manager/groups_manager.js"
import users from "@/Store/modules/users/users.js"
import chat_rules from "@/Store/modules/chat_rules/chat_rules.js"
import css_manager from "@/Store/modules/css_manager/css_manager.js"


export default createStore({
    modules: {
        auth,
        groups_manager,
        users,
        chat_rules,
        css_manager,
    },

})

