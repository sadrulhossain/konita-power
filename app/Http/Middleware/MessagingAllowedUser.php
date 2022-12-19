<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Common;

class MessagingAllowedUser {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $allowedUsers  = Common::getMessagingAllowedUsers();
        if(!in_array(Auth::user()->id,$allowedUsers)){
            return redirect('dashboard');
        }
        return $next($request);
    }

}
