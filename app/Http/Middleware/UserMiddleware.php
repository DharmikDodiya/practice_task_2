<?php

namespace App\Http\Middleware;

use App\Models\ModulePermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if(!Auth::check()){
            return error('You are Not Allow This Route');
        }
        else{
        $roles = $user->roles;
        foreach($roles as $role){

            foreach($role->permissions as $permission){

                foreach($permission->modulePermissions as $modulePermission){

                    $id =$modulePermission->id;
                    $data = ModulePermission::find($id);
                    if($data->create == true || $data->view == true || $data->update == true || $data->delete == true){
                        return $next($request);
                    }
                }
            }
        }
    }
    return error('not access this Module',type:'notfound');
    }
}
