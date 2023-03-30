<?php

namespace App\Http\Middleware;

use App\Models\ModulePermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public $module_code,$access;
    public function handle(Request $request, Closure $next ,$module_code , $access):Response
    {
        $user = Auth::user();
        if($user->hasAccess($module_code,$access)){
            return $next($request);    
        }
        // else{
        //     return "no";
        // }
    }
}
