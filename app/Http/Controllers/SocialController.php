<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Auth;
use App\Services\SocialGoogleAccountService;

class SocialController extends Controller
{
    /**
     * Create a redirect method to google api.
     *
     * @return void
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Return a callback method from google api.
     *
     * @return callback URL from google
     */
    public function callback(SocialGoogleAccountService $service)
    {
        $user = $service->createOrGetUser(Socialite::driver('google')->stateless()->user());
        
        // return $user;
        auth()->login($user);
        $token = $user->createToken('InvoiceAmigo')->accessToken;
        return response()->json(
            [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token
            ],
            200
        );
        // return redirect()->to('/dash');
    }
}
