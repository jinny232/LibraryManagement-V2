<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;

class QrLoginController extends Controller
{
    /**
     * Show the QR code login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->back();
        }
        return view('auth.login-qr');
    }

    /**
     * Handle login request using QR code value.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'qr_value' => 'required|string',
        ]);

        $qrValue = trim($request->input('qr_value'));

        // Find the member by member_id
        $member = Member::where('member_id', $qrValue)->first();

        if ($member) {
            // Log the member in using the 'member' guard
            Auth::guard('member')->login($member);


            // Redirect to member dashboard or homepage
            return redirect()->route('user.homepage');
        }

        // Return back with error if QR code is invalid
        return back()->withErrors([
            'qr_value' => 'Invalid QR code. Please try again.',
        ]);
    }

    /**
     * Log the member out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('member')->logout();

        return redirect()->route('login.qr.form');
    }
}
