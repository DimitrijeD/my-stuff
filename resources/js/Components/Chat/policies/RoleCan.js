/**
 * Class for determining what can role do with given Permissions
 */
export class RoleCan {
    constructor(user, permissions){
        this.user = user
        this.permissions = permissions
    }

    remove(participant){
        if(!this.actionRule_ParticipantNotSelf(participant.id)) return false

        if(!this.permissions.remove.includes(this.getPrticipantRole(participant))) return false 
        
        return true
    }

    removeAnybody(){
        return this.permissions.remove.length != 0 ? true : false
    }

    canPromoteDemote(participant){
        if(!this.actionRule_ParticipantNotSelf(participant.id, this.user.id)) return false

        let fromRoles = Object.keys(this.permissions.change_role)

        if(!this.actionRule_RuleTableNotEmpty(fromRoles)) return false

        if(!fromRoles.includes(this.getPrticipantRole(participant))) return false // participant is not among roles which can be changed under these conditions

        return true
    }

    actionRule_ParticipantNotSelf(participant_id){
        return participant_id == this.user.id ? false : true
    }

    actionRule_RuleTableNotEmpty(permissionKeys){
        return permissionKeys.length == 0 ? false : true
    }

    getPrticipantRole(participant){
        return participant.pivot.participant_role
    }

    canRemoveAnybody(){
        return this.permissions.remove.length != 0 ? true : false
    }
}