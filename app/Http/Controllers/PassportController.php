<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Business;
use Auth;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return response(200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 401);
        }
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('InvoiceAmigo')->accessToken;
            return response()->json(
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'token' => $token
                ],
                200
            );
        } else {
            return response()->json(['error' => 'Unauthorised - Incorrect credentials'], 401);
        }
    }

    public function user()
    {

        $user = auth()->user();
        // $user->roles = $user->roles
        if(Auth::user()->hasRole('business')){
            $user->isBusiness = true;
            // $user->business = Auth::user()->business();
            $user->business = auth()->user()->business;
        } else {
            $user->isBusiness = false;
        };
        return response()->json(['user' => $user], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged Out'], 200);
    }

    //Generates a password change token for 'unregistered' users
    public function generatePasswordChangeToken(Request $request) {
        $user = $request->user;
        $passwordChangeToken = str_random(60);
        DB::table('users')->where('id', $user->id)->update(['password_change_token' => $passwordChangeToken]);

        
    }
}
