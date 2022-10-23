/**
 * User must be in store and he must have had verified email 
 */
export default function auth_verified({ to, next, store, router }) 
{
    // if there is no user in store
    if(!store.getters.user){
        // fetch user 
        store.dispatch('getUser').then(()=>{
            // if user verified email, proceed, otherwise go to email verification page
            store.getters.user.email_verified_at 
                ? next() 
                : router.push({ name: 'EmailVerification' }) 
        }).catch((error) => {
            if(error.response.status == 401) {
                // if user is not logged in, go to Login
                router.push({ name: 'Login' }) 
            }
        })
    // user is in store and has verified email
    } else if(store.getters.user.email_verified_at) {
        // proceed
        next()
    } else {
        // user is in store but hasn't verified email
        router.push({ name: 'EmailVerification' }) 
    }
}
