<?php

namespace App\Http\Controllers\Admin\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse; // Import RedirectResponse

class MemberCreateController extends Controller
{
    /**
     * Display the member creation form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Store a newly created member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse // Change the return type
     */
    public function store(Request $request): RedirectResponse // Change the return type hint
    {
        // 1. Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'roll_no' => 'required|string',
            'year' => 'required|string',
            'major' => 'required|string',
            'gender' => 'required|string|in:male,female',
            'registration_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Calculate the expiration date
        $registrationDate = Carbon::parse($request->input('registration_date'));
        $expiredAt = $registrationDate->addYears(1);

        // 3. Collect all input data
        $data = $request->only([
            'name',
            'email',
            'phone_number',
            'roll_no',
            'year',
            'major',
            'gender',
            'registration_date',
        ]);

        // 4. Add the calculated expiration date to the data array
        $data['expired_at'] = $expiredAt;

        // 5. Handle the image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('members', 'public');
            $data['image'] = $imagePath;
        }

        // 6. Create the member with all the prepared data
        $member = Member::create($data);

        // 7. Generate and store the QR code
        $qrCodeSvg = QrCode::size(200)->generate(route('members.show', $member->member_id));
        $member->qr_code = $qrCodeSvg;
        $member->save();

        // 8. Redirect to the member index page with a success message
        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }
}
