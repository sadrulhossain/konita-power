<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CrmLeaderSuperAdminGroup {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if(Auth::user()->for_crm_leader != '1' && Auth::user()->group_id != '1'){
            return redirect('dashboard');
        }
        return $next($request);
    }

}
