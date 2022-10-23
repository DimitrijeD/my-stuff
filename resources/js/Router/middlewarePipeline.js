export default function middlewarePipeline(context, middleware, index) 
{
    if(!middleware.length) return context.next

    const nextMiddleware = middleware[index]

    if (!nextMiddleware) return context.next

    return () => {
        nextMiddleware({
            ...context,
            next: middlewarePipeline(context, middleware, index + 1),
        })
    }
}