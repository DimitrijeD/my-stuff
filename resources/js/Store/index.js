import { createStore } from 'vuex'
import auth from "@/Store/modules/auth/auth.js"
import groups_manager from "@/Store/modules/groups_manager/groups_manager.js"
import users from "@/Store/modules/users/users.js"
import chat_rules from "@/Store/modules/chat_rules/chat_rules.js"
// import action_responses from "@/Store/modules/action_responses/action_responses.js"
import action_response_manager from "@/Store/modules/action_response_manager/action_response_manager.js";

export default createStore({
    modules: {
        auth,
        groups_manager,
        users,
        chat_rules,
        // action_responses,
        action_response_manager,
    },

})

