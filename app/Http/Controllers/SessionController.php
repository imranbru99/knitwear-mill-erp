<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'is_active' => true], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Those credentials are not on the mill register.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = $request->user();
        if ($user->hasRole('Buyer')) {
            return redirect()->route('portal.buyer');
        }
        if ($user->hasRole('Supplier')) {
            return redirect()->route('portal.supplier');
        }

        return redirect()->intended('/admin');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
