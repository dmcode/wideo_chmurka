<?php

namespace App\Http\Controllers;

use App\Http\Requests\SingupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class UserController extends BaseController
{
    public function login()
    {

    }

    /**
     * @return View
     */
    public function singup(Request $request)
    {
        return view('user.singup');
    }

    public function singup_submit(SingupRequest $request)
    {
        $validatedData = $request->validated();

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->save();

        return view('user.singup');
    }
}
