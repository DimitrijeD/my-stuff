/**
 * User must be in store but his must be NOT verified ( email_verified_at = null )
 */
export default function auth_not_verified({ to, next, store, router })
{
    if(!store.getters.user){
        store.dispatch('getUser').then(()=>{
            store.getters.user.email_verified_at 
                ? router.push({ name: 'Profile' })  
                : next()
        }).catch((error) => {
            if(error.response.status == 401) {
                router.push({ name: 'Login' }) 
            }
        })
    } else if(store.getters.user.email_verified_at) {
        router.push({ name: 'Profile' })
    } else {
        next() 
    }
}