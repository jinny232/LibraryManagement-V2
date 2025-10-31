<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
   protected $fillable = [
    'title', 'author',  'isbn', 'total_copies', 'available_copies','shelf_id',
        'category_id','barcode','image'
];

     public function index()
    {
        $books = Book::all();
        return view('admin.books.index', compact('books'));
    }
    public function borrowings()
{
    return $this->hasMany(Borrowing::class, 'book_id');
}
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the shelf that owns the book.
     */
    public function shelf(): BelongsTo
    {
        return $this->belongsTo(Shelf::class);
    }
}
