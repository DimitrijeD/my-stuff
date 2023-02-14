export default () => { 
    return {
        group_id: null,
        text: '',
        files: [],
    }
}

/**
 * @user_id - int, id of user who is making new message
 * @group_id - int, group id
 * @text - string, message text
 * @files - array, files attached to message
 */