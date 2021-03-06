<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register user
     *
     * @param Request request
     */
    public function register(Request $request)
    {
        if($this->validate->register()){
            $user = User::create([
                'username'  => $request->username,
                'fname'  => $request->first,
                'lname'  => $request->last,
                'email' => $request->email,
                'pan'   => $request->pan,
                'mobile'   => $request->mobile,
                'password' => Hash::make($request->password)
            ]);
            $tokenResult = $user->createToken('authToken');
            $token = $tokenResult->token;
            $token->save();
            
            return $this->response([
                '_user' => $user, 
                '_token' => 'Bearer '.$tokenResult->token,
                '_end' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            ], true);
        }

        return $this->response($this->validate->errors);
    }
  
    /**
     * Login user and create token
     *
     * @param Request $request
     * 
     * @return array
     */
    public function login(Request $request)
    {
        if($this->validate->login()){
            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials))
                return $this->response('Invalid credential', false);

            $user = $request->user();

            $tokenResult = $user->createToken('authToken');
            $token = $tokenResult->token;
            
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(2);

            $token->save();

            return $this->response([
                '_user'  => $user,
                '_token' => 'Bearer '.$tokenResult->accessToken,
                '_end'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            ], true);
        }

        return $this->response($this->validate->errors);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * 
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->response('ok', true, 201);
    }
  
    /**
     * Get the authenticated User
     * 
     * @param Request $request
     *
     * @return array user object
     */
    public function user(Request $request)
    {
        return $this->response($request->user(), true);
    }
}
