<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'member' // Menetapkan nilai default role sebagai 'member'
        ]);

        return redirect('/halamanLogin')->with('success', 'Registration successful. Please login.');
    }
}
