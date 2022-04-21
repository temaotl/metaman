<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FakeController extends Controller
{
    public function login(int $id = null)
    {
        if (!App::environment(['local', 'testing'])) {
            dd('Only for `local` and `testing` environments!');
        }

        $user = User::findOrFail($id ?? request('id'));

        if (!$user->active) {
            return redirect('/blocked');
        }

        Auth::login($user);
        Session::regenerate();

        return redirect()->intended('/');
    }

    public function logout()
    {
        if (!App::environment(['local', 'testing'])) {
            dd('Only for `local` and `testing` environments!');
        }

        Auth::logout();
        Session::flush();

        return redirect('/');
    }
}
