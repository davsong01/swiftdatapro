<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return redirect()->back()->withInput($request->only('username'))->withErrors([
                'username' => 'The provided credentials are incorrect.',
            ]);
        }

        if (md5($request->password) == $user->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }else{
            return redirect()->back()->withInput($request->only('username'))->withErrors([
                'username' => 'The provided credentials are incorrect.',
            ]);
        }
        
        // dd($credentials, bcrypt('1A2b3c4d!@#'));
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended(route('admin.dashboard'));
        }
        
        return redirect()->back()->withInput($request->only('username'))->withErrors([
            'username' => 'The provided credentials are incorrect.',
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
