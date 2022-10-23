/**
 * Only guest can pass this middleware 
 * 
 * localStorage.getItem('alreadyAttemptedGetUser') var will prevent successive calls on fetch user
 */
export default function guest({ to, next, store, router })
{
    if(store.getters.user){
        router.back()       
    } else {
        if(localStorage.getItem('alreadyAttemptedGetUser') ){
            // get user was already executed by middleware
            // prevent future calls
            next() 
        } else {
            store.dispatch('getUser').then(()=>{
                // if user is logged in, go back, else proceed
                store.getters.user
                    ? router.back() 
                    : next() 
            }).catch((error) => {
                if(error.response.status == 401) {
                    // if user is not logged in, proceed
                    localStorage.setItem('alreadyAttemptedGetUser', true)
                    next() 
                }
            })
        }
    }
}