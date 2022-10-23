<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\MyStuff\Repos\ChatGroup\ChatGroupEloquentRepo;

class ChatGroupAccess
{
    protected $chatGroupRepo;

    public function __construct(ChatGroupEloquentRepo $chatGroupRepo)
    {
        $this->chatGroupRepo = $chatGroupRepo;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->group_id)
            return response()->json(['errors' => __("Group doesn't exist.")], 404);

        if(!$user = auth()->user())
            return response()->json(['errors' => __("Unauthenticated")], 403);

        $group = $this->chatGroupRepo->first(
            ['id' => $request->group_id], 
            ['participants']
        );

        if(!$group)
            return response()->json(['errors' => __("Group doesn't exist.")], 404);

        if($group->participants->contains($user)){
            $request->merge([
                "user" => $user,
                "group" => $group,
            ]);
    
            return $next($request);
        }

        return response()->json(['errors' => __("You have no access rights to this chat group.")], 403);
    }
}
