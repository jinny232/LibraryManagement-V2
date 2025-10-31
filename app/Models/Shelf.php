<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shelf extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shelf_number',
        'row_number',
        'sub_col_number',
    ];

    /**
     * Get the books for the shelf.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
