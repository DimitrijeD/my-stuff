<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\MyStuff\Repos\ChatGroup\ChatGroupEloquentRepo;
use App\Exceptions\UnAuthrorizedException;
use App\Exceptions\ModelDoesntExistException;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\UnAuthenticatedException;

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
            throw new InternalServerErrorException(__('serverError.failed'));

        if(!$user = auth()->user())
            throw new UnAuthenticatedException();

        if(!$group = $this->chatGroupRepo->first(
            ['id' => $request->group_id], 
            ['participants']
        )) throw new ModelDoesntExistException(__('model.groupNotFound'));
        
        if($group->participants->contains($user)){
            $request->merge([
                "user" => $user,
                "group" => $group,
            ]);

            return $next($request);
        }

        throw new UnAuthrorizedException(__('chat.noAccess'));
    }
}
