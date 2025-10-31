<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'requester_name',
        'requester_email',
        'requester_phone',
    ];
}
