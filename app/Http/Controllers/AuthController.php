<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Hash;
use Route;

class AuthController extends Controller
{
    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }

    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:3|confirmed',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->email_token = md5(now().$user->name.$user->email.$user->password);
        $user->save();
        event(new Registered($user));


        return response()->json(['status' => 'success'], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            $user_verified = User::where('id', Auth::user()->id)->whereNotNull('email_verified_at')->exists();
        
            if ($user_verified) {
                return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
            } else {
                $this->guard()->logout();
                return response()->json(['error' => 'email_not_verified'], 401);
            }
        }

        return response()->json(['error' => 'login_error'], 401);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');

        $email_exists = User::where('email', '=', $email)->exists();
        
        if ($email_exists) {
            return response()->json([
                'status' => 'email_exists',
            ], 200);    
        } else {
            return response()->json([
                'status' => 'ok',
            ], 200);
        }
    }

    public function verifyEmail($user_id, $hash)
    {
        $user = User::where('id', $user_id)->where('email_token', $hash);
        if (!$user->exists()) return redirect('/login?userNotFound=1');
        $user = $user->first();
        $user->markEmailAsVerified();
        return redirect('/login?emailVerified=1');
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->avatar = 'https://gravatar.com/avatar/'. md5($user->email) .'?s=40&d=robohash&r=x';

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh(Request $request)
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'success'], 200)
                ->header('Authorization', $token);
        }

        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    private function guard()
    {
        return Auth::guard();
    }

    public function sendPasswordResetLink(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Handle reset password 
     */
    public function callResetPassword(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();

        event(new PasswordReset($user));
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Password reset successfully.']);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Failed, Invalid Token.']);
    }
}