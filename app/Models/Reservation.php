<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'member_id',
        'status',
        'reservation_date',
        'expiration_date',
    ];

    /**
     * Get the book associated with the reservation.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the member associated with the reservation.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }
}
