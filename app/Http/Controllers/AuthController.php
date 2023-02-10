<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SingupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class AuthController extends BaseController
{
    public function login()
    {
        return view('auth.login');
    }

    public function login_submit(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->route('index');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }

    /**
     * @return View
     */
    public function singup(Request $request)
    {
        return view('auth.singup');
    }

    public function singup_submit(SingupRequest $request)
    {
        $validatedData = $request->validated();

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->save();

        return redirect()->route('login');
    }
}
