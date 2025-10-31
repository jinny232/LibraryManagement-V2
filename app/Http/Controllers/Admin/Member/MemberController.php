<?php

namespace App\Http\Controllers\Admin\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
// Make sure this line is present at the top of your file.
use Picqer\Barcode\BarcodeGeneratorPNG;

class MemberController extends Controller
{
    /**
     * Converts an integer year (1, 2, 3...) to a string (1st Year, 2nd Year, etc.).
     *
     * @param int|null $yearInt
     * @return string|null
     */
    private function getYearAsString(?int $yearInt): ?string
    {
        if (is_null($yearInt)) {
            return null;
        }

        // Use an array lookup for cleaner code instead of a switch statement.
        $suffixes = [1 => '1st', 2 => '2nd', 3 => '3rd'];
        $suffix = $suffixes[$yearInt] ?? $yearInt . 'th';

        return "{$suffix} Year";
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        // 1. Build the base query with all filters applied.
        $baseQuery = Member::query();

        // Retrieve and apply filters
        $search = $request->input('search');
        $major = $request->input('major');
        $yearInt = $request->input('year');
        $gender = $request->input('gender');

        $baseQuery->when($search, function ($q, $search) {
            return $q->where('name', 'like', "%{$search}%")
                ->orWhere('roll_no', 'like', "%{$search}%");
        });

        $baseQuery->when($major, function ($q, $major) {
            return $q->where('major', $major);
        });

        $baseQuery->when($yearInt, function ($q, $yearInt) {
            return $q->where('year', $yearInt);
        });

        $baseQuery->when($gender, function ($q, $gender) {
            return $q->where('gender', $gender);
        });

        // 2. Clone the base query for the paginated table.
        $members = (clone $baseQuery)->paginate(20)->appends($request->only('search', 'major', 'year', 'gender'));

        // Add the string version of the year to each member object
        $members->getCollection()->each(function ($member) {
            $member->year_string = $this->getYearAsString($member->year);
        });

        // 3. Clone the base query for the charts to ensure they are also filtered.
        $membersByMajor = (clone $baseQuery)
            ->select('major', DB::raw('count(*) as total'))
            ->groupBy('major')
            ->orderBy('total', 'desc')
            ->get();

        $membersByYear = (clone $baseQuery)
            ->select('year', DB::raw('count(*) as total'))
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        // New query to get members by gender for the pie chart
        $membersByGender = (clone $baseQuery)
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();


        // Prepare data for the charts
        $majorsLabels = $membersByMajor->pluck('major');
        $majorsData = $membersByMajor->pluck('total');

        $yearsLabels = $membersByYear->map(fn ($item) => $this->getYearAsString($item->year));
        $yearsData = $membersByYear->pluck('total');

        // 4. Get unique majors and years for the dropdowns.
        $majors = Member::distinct()->orderBy('major')->pluck('major');

        $years = collect(range(1, 5))->mapWithKeys(function ($yearInt) {
            return [$yearInt => $this->getYearAsString($yearInt)];
        })->toArray();

        return view('admin.members.index', compact('members', 'majors', 'years', 'majorsLabels', 'majorsData', 'yearsLabels', 'yearsData', 'membersByMajor', 'membersByYear', 'membersByGender'));
    }

    /**
     * Show the form for creating a new member.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $majors = Member::distinct()->orderBy('major')->pluck('major');
        $years = collect(range(1, 5))->mapWithKeys(function ($yearInt) {
            return [$yearInt => $this->getYearAsString($yearInt)];
        })->toArray();

        return view('admin.members.create', compact('majors', 'years'));
    }

    /**
     * Store a newly created member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'roll_no' => 'required|string|max:255|unique:members,roll_no',
            'major' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:5',
            'gender' => 'required|string|in:Male,Female,Other',
        ]);

        Member::create($validatedData);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified member.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\View\View
     */
    public function show(Member $member): View
    {
        // Add the string version of the year to the member object
        $member->year_string = $this->getYearAsString($member->year);

        // Generate the QR code for the member's roll number
        // We're using SVG format to avoid Imagick dependency issues.
        $qrCode = QrCode::format('svg')->size(200)->generate($member->roll_no);

        // Generate the barcode for the member's roll number.
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($member->roll_no, $generator::TYPE_CODE_128));

        return view('admin.members.show', compact('member', 'qrCode', 'barcode'));
    }

    /**
     * Renders the member card for printing.
     *
     * @param \App\Models\Member $member
     * @return \Illuminate\View\View
     */
    public function printCard(Member $member): View
    {
        $member->year_string = $this->getYearAsString($member->year);

        // Generate QR code for the view
        $qrCode = QrCode::format('svg')->size(80)->generate($member->roll_no);

        return view('admin.members.card', compact('member', 'qrCode'));
    }

    /**
     * Show the form for editing the specified member.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\View\View
     */
    public function edit(Member $member): View
    {
        $majors = Member::distinct()->orderBy('major')->pluck('major');
        $years = collect(range(1, 5))->mapWithKeys(function ($yearInt) {
            return [$yearInt => $this->getYearAsString($yearInt)];
        })->toArray();

        return view('admin.members.edit', compact('member', 'majors', 'years'));
    }

    /**
     * Update the specified member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Member $member): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'roll_no' => 'required|string|max:255|unique:members,roll_no,' . $member->member_id . ',member_id',
            'major' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:5',
            'gender' => 'required|string|in:Male,Female,Other',
            'phone_number' => 'nullable|string|max:20',
            'registration_date' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }

            // Store the new image and get the path
            $imagePath = $request->file('image')->store('members', 'public');

            // Add the image path to the validated data array
            $validatedData['image'] = $imagePath;
        }

        $member->update($validatedData);

        // Standardized redirect route name for consistency
        return redirect()->route('admin.members.index')
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified member from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member deleted successfully.');
    }

    /**
     * Exports a PDF for a given member.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Member $member)
    {
        // Use the Facade for simpler and more reliable PDF generation.
        $pdf = Pdf::loadView('admin.members.pdf_template', compact('member'));
        return $pdf->download("member-{$member->member_id}.pdf");
    }

    /**
     * Exports a QR Code for a given member.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function exportQrCode(Member $member)
    {
        // The previous error was here. We are now using SVG format to avoid Imagick dependency.
        $qrCode = QrCode::format('png')->size(300)->generate($member->roll_no);

        // Return a downloadable response with the correct headers for SVG.
        return Response::streamDownload(function () use ($qrCode) {
            echo $qrCode;
        }, "member-{$member->roll_no}-qrcode.png", [
            'Content-Type' => 'image/svg+xml',
        ]);
    }


}
