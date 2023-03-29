<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PasswordResetMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PasswordReset;
use App\Notifications\WelcomeMessageNotification;

use function PHPSTORM_META\type;

class AuthController extends Controller
{
    /**
    * user Register Method
    */
    public function register(Request $request){
        $request->validate([
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'email'                 => 'required|unique:users,email|email',
            'phone'                 => 'required|unique:users,phone|min:10|max:10',
            'role_ids'              => 'exists:roles,id|array',
            'password'              => 'required|same:password_confirmation|min:6|max:12',
            'password_confirmation' => 'required',
        ]);
        
        $user = User::create($request->only(['first_name','last_name','email','phone'])
        +[
            'token'     => Str::random(64),
            'password'  => Hash::make($request->password),
        ]
        );
        $user->roles()->attach($request->role_ids);
        $user->notify(new WelcomeMessageNotification($user));
        return success('User Data',$user);
    }

    /**
    * user Verify Mail
    */

    public function verifyAccount($token)
    {
        $verifyAccount = User::where('token', $token)->first();

       
        if(!is_null($verifyAccount) ){
                $verifyAccount->status = 1;
                $verifyAccount->email_verified_at = now();
                $verifyAccount->token = '';
                $verifyAccount->save();

            return success('Your Mail Is Verified Login Now');
        } 
        return error('Your Email Is Already Verified',type:'unauthenticated');        
    }

    /**
    * user Login 
    */

     public function login(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);

        if(Auth::attempt(['email' =>$request->email,'password' => $request->password , 'status' => 1])){
            $user = User::where('email', $request->email)->first();
            return success('You Are Login Now',$user->createToken("API TOKEN")->plainTextToken,200);
        }
            return error('Invalid Email Or Password',type:'unauthenticated');
        }

    /**
    * Forget Password  
    */

    public function forgetPassword(Request $request){
            
        $request->validate([
            'email'            => 'required|email|exists:users,email',
        ]);
        $user = User::where('email',$request->email)->first();

           
        $userdata  = PasswordReset::updateOrCreate(
            ['email'    =>  $request->email],
            [
                'email'         =>  $request->email,
                'token'         =>  Str::random(64),
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'expired_at'    =>  now()->addDays(2),
            ]);

            $user['token']  = $userdata->token;
            $user->notify(new PasswordResetMail($user));
            return success('send mail please check your mail',['token' => $userdata->token]);
    }

    /**
    * password reset 
    */
    public function resetPassword(Request $request){
        $request->validate([
            'password'              => 'required|same:password_confirmation',
            'token'                 => 'required|exists:password_resets,token',
            'password_confirmation' =>  'required|same:password',
        ]);
        $data = PasswordReset::where('token',$request->token)->first();
        $expiredate = $data->expired_at >= $data->created_at;
        
        if($expiredate){
            User::updateOrCreate([
                'email'     => $data->email
            ],[
                'password'  => Hash::make($request->password)
            ]);

            $data->delete();
            return success('Password Reset Successfully');

        }
            return error('Password Reset Token Expired',type:'unauthenticated');
    }
}
