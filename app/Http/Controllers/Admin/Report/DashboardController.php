<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBorrowed = Borrowing::count();

        $currentlyBorrowed = Borrowing::where('status', 'borrowed')->count();

        $overdueCount = Borrowing::whereNull('return_date')
            ->where('due_date', '<', now())
            ->count();

        // Bundle the statistics into a single array
        $dashboardStats = [
            'totalBorrowed' => $totalBorrowed,
            'currentlyBorrowed' => $currentlyBorrowed,
            'overdueCount' => $overdueCount,
        ];

        $mostBorrowedBooks = Borrowing::select('book_id', DB::raw('count(*) as borrow_count'))
            ->groupBy('book_id')
            ->orderByDesc('borrow_count')
            ->with('book')
            ->take(5)
            ->get();

        $topMembers = Borrowing::select('member_id', DB::raw('count(*) as borrow_count'))
            ->groupBy('member_id')
            ->orderByDesc('borrow_count')
            ->with('member')
            ->take(5)
            ->get();

        // This is a new query to get the recent borrowings
        $recentBorrowings = Borrowing::with(['book', 'member'])
            ->orderByDesc('borrow_date')
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'dashboardStats', 'mostBorrowedBooks', 'topMembers', 'recentBorrowings'
        ));
    }
}
