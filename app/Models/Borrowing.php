<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $primaryKey = 'borrow_id';

    protected $fillable = [
        'member_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'renewal_count' // New field added here
    ];

    public function getRouteKeyName()
    {
        return 'borrow_id';
    }

    protected $casts = [
        'borrow_date' => 'datetime',
        'due_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
