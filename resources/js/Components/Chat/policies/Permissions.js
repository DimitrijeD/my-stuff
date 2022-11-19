export class Permissions {
    constructor(actionKeys, rules, chatRole, model_type) {
        this.actionKeys = actionKeys
        this.rules = rules
        this.chatRole = chatRole
        this.model_type = model_type
        this.permissions = this.createObjectFromArrayValues(this.actionKeys)
    }

    /**
     * Creates object of permissions user has in specific chat group
     */
    create(){
        this.permission_canAdd()
        this.permission_canRemove()
        this.permission_canChangeRole()
        this.permission_canSendMessage()
        this.permission_canChangeGroupName()
        this.permission_canChangeGroupType()

        return this.permissions
    }

    permission_canAdd(){
        let action = 'add'
        this.ruleDepth3(this.rules[action][this.chatRole], action)
    }

    permission_canRemove(){
        let action = 'remove'
        this.ruleDepth3(this.rules[action][this.chatRole], action)
    }

    permission_canChangeRole(){
        let action = 'change_role'
        this.ruleDepth4(this.rules[action][this.chatRole], action)
    }

    permission_canSendMessage(){
        let action = 'send_message'
        this.ruleDepth2(this.rules[action][this.chatRole], action)
    }

    permission_canChangeGroupName(){
        let action = 'change_group_name'
        this.ruleDepth2(this.rules[action][this.chatRole], action)
    }

    permission_canChangeGroupType(){
        let action = 'change_group_type'
        this.ruleDepth2(this.rules[action][this.chatRole], action)
    }

    ruleDepth2(level1, action){
        this.permissions[action] = level1[this.model_type] ? true : false
    }

    ruleDepth3(level1, action){
        for(let targetRole in level1){
            if(level1[targetRole][this.model_type]) this.permissions[action].push(targetRole)
        }
    }

    ruleDepth4(level1, action){
        this.permissions[action] = {}

        for(let fromRole in level1){
            for(let toRole in level1[fromRole]){
                if(level1[fromRole][toRole][this.model_type] ){
                    
                    if(!this.permissions[action][fromRole]){
                        this.permissions[action][fromRole] = []
                    }

                    this.permissions[action][fromRole].push(toRole)
                }
            }
        }
    }

    createObjectFromArrayValues(array){
        let object = {}

        for(let i = 0; i < array.length; i++){
            object[array[i]] = []
        }

        return object
    }
};