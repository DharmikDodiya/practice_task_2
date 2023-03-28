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
        //dd($user);
        $roles = $user->roles;
        if(!Auth::check()){
            return error('You are Not Allow This Route');
        }
        else{
        foreach($roles as $role){
            //dd($role);
            foreach($role->permissions as $permission){
                //dd($role,$permission);
                foreach($permission->modulePermissions as $modulePermission){
                    //dd($modulePermission);
                    //dd($modulePermission->permissions);
                    $id =$modulePermission->id;
                    //dd($id);
                    $data = ModulePermission::find($id);
                   //dd($data);
                    //$data = ModulePermission::where('create',true && 'view',true && 'update',true && 'delete',true )->first();
                    if($data->create == true || $data->view == true || $data->update == true || $data->delete == true){
                        //return success('success Ok!!!');
                        return $next($request);
                    }
                }
            }
        }
    }
    return error('not access this Module',type:'notfound');
    }
}
