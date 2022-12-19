<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CrmMemberSuperAdminGroup {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if(Auth::user()->allowed_for_crm != '1' && Auth::user()->group_id != '1'){
            return redirect('dashboard');
        }
        return $next($request);
    }

}
