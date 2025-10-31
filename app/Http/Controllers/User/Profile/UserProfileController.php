<?php

namespace App\Http\Controllers\User\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Response;
class UserProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        // Get the authenticated member's ID from the session
        $memberId = Auth::guard('member')->user()->member_id;

        // Find the member in the database
        $member = Member::find($memberId);

        // This check acts as a fallback to the middleware
        if (!$member) {
            return redirect()->route('logout');
        }

        // Generate the QR code as a string (SVG)
        $qrCode = QrCode::size(200)->generate($member->member_id);

        // Pass the member data and the QR code string to the view
        return view('user.profile.index', compact('member', 'qrCode'));
    }

    /**
     * Update the user's profile.
     */

// Existing update method (remains the same as your previous corrected code)

public function update(Request $request)
{
    $memberId = session('member_id');
    $member = Member::find($memberId);

    if (!$member) {
        return redirect()->route('logout');
    }

    $validatedData = $request->validate([
        'email' => 'required|string|email|max:50|unique:members,email,' . $member->member_id . ',member_id',
        'phone_number' => 'nullable|string|max:11',
    ]);

    $member->update($validatedData);

    return redirect()->back()->with('success', 'Profile updated successfully!');
}


// New method for handling image uploads
public function updateImage(Request $request)
{
    $memberId = session('member_id');
    $member = Member::find($memberId);

    if (!$member) {
        return redirect()->route('logout');
    }

    // Validate only the image
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Check for and delete the old image
    if ($member->image && Storage::disk('public')->exists($member->image)) {
        Storage::disk('public')->delete($member->image);
    }

    // Store the new image and get its path
    $imagePath = $request->file('image')->store('members', 'public');

    // Update the member's image field in the database
    $member->image = $imagePath;
    $member->save();

    return redirect()->back()->with('success', 'Profile image updated successfully!');
}
public function exportQrCode(){}
}
