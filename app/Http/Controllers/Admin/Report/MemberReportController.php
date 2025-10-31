<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberReportController extends Controller
{
    public function index(Request $request)
    {
        // Start a query on the Member model
        $query = Member::query();

        // Check if a search query is present
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('roll_no', 'like', "%{$search}%");
        }

        // Apply pagination. I've set it to 10 items per page as a sensible default.
        // The `withQueryString()` method is crucial to maintain the search filter across pages.
        $members = $query->paginate(10)->withQueryString();

        // The data for the charts needs to be retrieved separately, based on the current search results
        $all_filtered_members = $query->get();

        $membersPerMajor = $all_filtered_members->groupBy('major')->map->count();
        $membersPerYear = $all_filtered_members->groupBy('year')->map->count();
        $membersByGender = $all_filtered_members->groupBy('gender')->map->count();

        return view('admin.reports.memberreport', compact('members', 'membersPerMajor', 'membersPerYear', 'membersByGender'));
    }
}
