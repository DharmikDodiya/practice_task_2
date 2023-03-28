<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ListingApiTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * change password 
     */
    public function changePassword(Request $request){
        $request->validate([
            'current_password'      =>  'required|current_password',
            'password'              =>  'required|min:8|max:12',
            'password_confirmation' =>  'required|same:password', 
        ]);

        $id = Auth::user();
        $user = User::find($id)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return success('password Change Successfully');
    }

    /**
     * logout user
     */
    public function logout()
    {
        $user = auth()->user()->tokens();
        $user->delete();
        return success('you are logout naow');
    }

    /**
     * get user role by user id
     */
    public function get($id){
        $user = User::with('roles')->find($id);

        if($user){
            return success('User Details',$user);
        }
            return error(type:'notfound');
    }

    /**
     * login user profile
     */
    public function userProfile(){
        $userid = Auth::user()->id;
        $user = User::with('roles')->find($userid);
        if($user){
            return success('user Profile Details',$user);
        }
            return error(type:'notfound');        
    }

    /**
    * update User
    */

    public function update( Request $request){
        $request->validate([
            'first_name'            => 'string',
            'last_name'             => 'string',
            'phone'                 => 'min:10|max:10',
            'role_ids'              => 'exists:roles,id|array',
        ]);
        $user = Auth::user();
        if($user){
            $user->update($request->only('first_name','last_name','phone'));
            $user->roles()->syncWithoutDetaching($request->role_ids);
            return success('User Data Updated Successfully',$user);
        }
        return error('User Data Not Updated',type:'notfound');
    }
}
