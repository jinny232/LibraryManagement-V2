<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
               return  back();
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:20',
            'password' => 'required|max:20',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        // Check if an admin with the provided email exists
        if (!$admin) {
            return back()->with('error', 'The provided email address is not registered.');
        }

        // Check if the provided password matches the stored password
        if ($request->password === $admin->password) {
            // Log in the admin using the Auth facade
            Auth::guard('admin')->login($admin);

            // Redirect to the admin dashboard
            return redirect()->intended('/admin/dashboard');
        }

        // If the password does not match
        return back()->with('error', 'The password you entered is incorrect.');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('admin.login');
    }
}
