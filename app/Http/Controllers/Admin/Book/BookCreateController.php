<?php

namespace App\Http\Controllers\Admin\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Str;

class BookCreateController extends Controller
{
    /**
     * Show the form for creating a new book.
     */
    public function create(): View
    {
        // Get distinct categories for the select dropdown (filter null/empty)
        $categories = Book::distinct()->pluck('category')->filter()->sort()->values()->all();

        return view('admin.books.create', compact('categories'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request data.
        $validatedData = $request->validate([
            'title'            => 'required|string|max:255',
            'author'           => 'required|string|max:255',
            'category'         => 'required|string|max:255',
            'isbn'             => 'required|string|unique:books',
            'total_copies'     => 'required|integer|min:1',
            'shelf_number'     => 'required|string|max:20',
            'row_number'       => 'required|string|max:20',
            'sub_col_number'   => 'required|string|max:20',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 2. Handle the image upload and get the file path.
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Check if the file is valid before storing
            $file = $request->file('image');
            if ($file->isValid()) {
                // Use the 'public' disk, which maps to `storage/app/public`
                // and then link it to the public folder.
                $imagePath = $file->store('images/books', 'public');
            } else {
                // You can add a logging statement here for debugging purposes
                \Log::warning('Image upload failed for an unknown reason.');
            }
        }

        // 3. Generate barcode
        $barcodeGenerator = new DNS1D();
        $barcodeGenerator->setStorPath(public_path('barcode/'));
        $barcodeSvg = $barcodeGenerator->getBarcodeSVG($validatedData['isbn'], 'C128', 2, 50);

        // 4. Combine all data and create the book record.
        $book = Book::create(array_merge($validatedData, [
            'image' => $imagePath,
            'barcode' => $barcodeSvg,
            'available_copies' => $validatedData['total_copies'],
        ]));

        return view('admin.books.confirm', compact('book'));
    }
}
