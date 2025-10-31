<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Support\Facades\DB;

class BorrowingReportController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::select(
                DB::raw('MONTH(borrow_date) as month'),
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($item) => [
                'month' => 'Month ' . $item->month,
                'total' => $item->total
            ]);

        return view('admin.reports.borrowingreport', compact('borrowings'));
    }
}
