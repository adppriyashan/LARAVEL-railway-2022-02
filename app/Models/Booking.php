<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'turn', 'start', 'end', 'date', 'user'];

    public function seatsdata()
    {
        return $this->hasMany(BookingSeat::class, 'booking', 'id');
    }

    public function startdata()
    {
        return $this->hasOne(Location::class, 'id', 'start');
    }

    public function enddata()
    {
        return $this->hasOne(Location::class, 'id', 'end');
    }

    public function userdata()
    {
        return $this->hasOne(User::class, 'id', 'user');
    }
}
