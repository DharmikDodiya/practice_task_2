<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PasswordResetMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
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

            return success('Your Mail Is verified login now');
            } 
        else{
            return error('Your Email Is Already Verified',type:'unauthenticated');        
        }
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

            return response()->json([
                'message'   =>'You Are Login Now',
                'token'     => $user->createToken("API TOKEN")->plainTextToken,
                'status'    => 200
            ]);

            return success('You Are Login Now',$user->createToken("API TOKEN")->plainTextToken,200);
        }
        else{
            return error('Invalid Email Or Password',type:'unauthenticated');
        }
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
        $count = PasswordReset::where('token',$request->token)->first();
        $expiredate = $count->expired_at >= $count->created_at;
        
        if($expiredate){
            User::updateOrCreate([
                'email'     => $count->email
            ],[
                'password'  => Hash::make($request->password)
            ]);

            $count->delete();
            return success('Password Reset Successfully');

        }
        else{
            return error('Password Reset Token Expired',type:'unauthenticated');
        }
    }
}
