<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use Notifiable;

    // Override the default primary key name 'id'
    protected $primaryKey = 'member_id';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'roll_no',
        'year',
        'major',
        'gender',
        'registration_date',
        'qr_code',
        'expired_at',
        'image',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'member_id');
    }

    public function getRouteKeyName()
    {
        return 'roll_no';
    }
}
