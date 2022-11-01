/**
 * How does this module work:
 * it stores response messages it received from other modules. Then named ActionResponses modules instantuated
 * in desired places (lets say 'main' one which says 'logged in', 'succ registratioin' etc.)
 * 
 * Once that component sees that id changed, it checks if it has that response message, 
 * if it does, then it tell this module to delete data because message has already or will be flashed to screen. 
 * If it doesn't have messages with this id, it will store data, render message and tell module to ddelete response data 
 * 
 * This cycle allows only one instance of response to be stored inside this module, while ID increments , identifying new messages
 * Id is neccessary piece of data, because it will allow component to determine what action to take. 
 */
export default () => { 
    return {
        messages: [],
        response_type: '',
        id: 1
    }
}