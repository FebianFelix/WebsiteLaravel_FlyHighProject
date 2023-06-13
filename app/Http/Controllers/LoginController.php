<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return view('sudah_login');
        } else {
            return view('index');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        $user = User::where('email', $credentials['email'])->first();
    
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }
    
        // Store user details in session
        $request->session()->put('name', $user->name);
        $request->session()->put('email', $user->email);
        $request->session()->put('password', $user->password);
    
        if ($user->role === 'admin') {
            return redirect('/indexAdmin');
        } elseif ($user->role === 'member') {
            return redirect('/indexLogin');
        } else {
            // Handle other roles or redirect to a default page
            return redirect('/indexLogin');
        }
    }
    

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}

