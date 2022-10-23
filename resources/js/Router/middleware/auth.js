/**
 * User must be in store
 */
export default function auth({ to, next, store, router }) 
{
    if(store.getters.user){
        next()
    } else {
        store.dispatch('getUser').then(()=>{
            next()
        }).catch((error) => {
            if(error.response.status == 401) {
                router.push({name: "Login"}) 
            }
        })
    }
}
