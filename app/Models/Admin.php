<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class Admin extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    // Set the primary key
    protected $primaryKey = 'admin_id';

    // The attributes that are mass assignable.
    protected $fillable = [
        'email',
        'phone_no',
        'address',
        'password',
    ];

    // The attributes that should be hidden for serialization.
    protected $hidden = [
        'password',
    ];
}
