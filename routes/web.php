<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Member\MemberController;
use App\Http\Controllers\Admin\Member\MemberCreateController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ShelfController;
use App\Http\Controllers\Admin\Book\BookController;
use App\Http\Controllers\Admin\Book\BookCreateController;
use App\Http\Controllers\Admin\Borrowing\BorrowingController;
use App\Http\Controllers\Admin\Report\ChartController;
use App\Http\Controllers\Admin\Report\BookReportController;
use App\Http\Controllers\Admin\Report\MemberReportController;
use App\Http\Controllers\Admin\Report\BorrowingReportController;
use App\Http\Controllers\Admin\Report\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Auth\QrLoginController;
use App\Http\Controllers\User\Dashboard\UserDashboardController;
use App\Http\Controllers\User\Book\UserBookController;
use App\Http\Controllers\User\Book\BookRequestController;
use App\Http\Controllers\User\Book\UserBookingsController;
use App\Http\Controllers\User\Borrowed\UserBorrowedController;
use App\Http\Controllers\User\Profile\UserProfileController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\MemberMiddleware;

// -----------------------------
// Public Routes
// -----------------------------
Route::get('/', [UserDashboardController::class, 'index'])->name('homepage');

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::get('/login-qr', [QrLoginController::class, 'showLoginForm'])->name('login.qr.form');
Route::post('/login-qr', [QrLoginController::class, 'login'])->name('login.qr.submit');

Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('login.qr.form');
})->name('logout');

// -----------------------------
// Admin Routes (Protected by AdminMiddleware)
// -----------------------------
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
Route::get('/members/{member}/card', [MemberController::class, 'printCard'])->name('members.printCard');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Books
    Route::get('books/available', [BookController::class, 'available'])->name('books.available');
    Route::get('books/create', [BookCreateController::class, 'create'])->name('books.create');
    Route::post('books', [BookCreateController::class, 'store'])->name('books.store');
    Route::get('books/{book}/export-barcode', [BookController::class, 'exportBarcode'])->name('books.exportBarcode');
    Route::get('books/{book}/export-pdf', [BookController::class, 'exportPdf'])->name('books.exportPdf');
    Route::get('books/{book}/barcode', [BookController::class, 'generateBarcodePage'])->name('books.barcode');
    Route::get('books/{id}/confirm', [BookController::class, 'confirm'])->name('books.confirm');
    Route::resource('books', BookController::class);

    // Categories
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Shelves
    Route::post('shelves', [ShelfController::class, 'store'])->name('shelves.store');
    Route::put('shelves/{shelf}', [ShelfController::class, 'update'])->name('shelves.update');
    Route::delete('shelves/{shelf}', [ShelfController::class, 'destroy'])->name('shelves.destroy');

    // Borrowings
    Route::prefix('borrowings')->name('borrowings.')->group(function () {
        Route::get('/', [BorrowingController::class, 'index'])->name('index');
        Route::get('/create', [BorrowingController::class, 'create'])->name('create');
        Route::post('/', [BorrowingController::class, 'store'])->name('store');
        Route::get('/pending-return', [BorrowingController::class, 'pendingReturn'])->name('pending-return');
        Route::patch('/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('return');
        Route::get('/returned', [BorrowingController::class, 'returned'])->name('returned');
        Route::get('/overdue', [BorrowingController::class, 'overdue'])->name('overdue');
        Route::get('/{id}', [BorrowingController::class, 'show'])->name('show');
        Route::post('/{borrowing}/renew', [BorrowingController::class, 'renewBook'])->name('renew');
    });

    // Reports
    Route::get('reports/charts', [ChartController::class, 'index'])->name('reports.charts');
    Route::get('reports/books', [BookReportController::class, 'index'])->name('reports.bookreport');
    Route::get('reports/members', [MemberReportController::class, 'index'])->name('reports.memberreport');
    Route::get('reports/borrowings', [BorrowingReportController::class, 'index'])->name('reports.borrowings');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});

// -----------------------------
// Member Routes (Protected by MemberMiddleware)
// -----------------------------
Route::middleware([MemberMiddleware::class])->prefix('user')->name('user.')->group(function () {

    Route::get('/homepage', [UserDashboardController::class, 'index'])->name('homepage');

    // Books
    Route::get('/books', [UserBookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [UserBookController::class, 'show'])->name('books.show');
    Route::post('/books/request', [BookRequestController::class, 'store'])->name('book.request');
    Route::post('/books/borrow', [UserBookController::class, 'borrow'])->name('book.borrow');
    Route::get('/bookings', [UserBookingsController::class, 'index'])->name('bookings.index');

    // Borrowed
    Route::get('/borrowed', [UserBorrowedController::class, 'index'])->name('borrowed');

    // Profile
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::patch('/profile/image', [UserProfileController::class, 'updateImage'])->name('profile.updateImage');
    Route::patch('/user/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/qr-code/export', [UserProfileController::class, 'exportQrCode'])->name('profile.exportQrCode');
});

// -----------------------------
// Member Management (Admin Controllers)
// -----------------------------
Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::get('/members/create', [MemberCreateController::class, 'create'])->name('members.create');
Route::post('/members', [MemberCreateController::class, 'store'])->name('members.store');
Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
Route::put('/members/{member:member_id}', [MemberController::class, 'update'])->name('members.update');
Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');

// Member export routes
Route::get('members/{member}/export-pdf', [MemberController::class, 'exportPdf'])->name('members.exportPdf');
Route::get('members/{member}/export-qrcode', [MemberController::class, 'exportQrCode'])->name('members.exportQrCode');
