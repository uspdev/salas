<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Socialite;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function redirectToProvider()
    {
        return Socialite::driver('senhaunica')->redirect();
    }

    public function handleProviderCallback()
    {
        $userSenhaUnica = Socialite::driver('senhaunica')->user();
        $user = User::where('codpes',$userSenhaUnica->codpes)->first();

        if (is_null($user)) $user = new User;

        // bind do dados retornados
        $user->codpes = $userSenhaUnica->codpes;
        $user->email = $userSenhaUnica->email;
        $user->name = $userSenhaUnica->nompes;
        $user->save();
        Auth::login($user, true);
        return redirect('/');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/');
    }
}
