<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    use HasFactory;

    protected $fillable = ['booking', 'turn', 'seat', 'start', 'end', 'starttime', 'endtime', 'status'];

    public function startdata()
    {
        return $this->hasOne(Location::class, 'id', 'start');
    }

    public function enddata()
    {
        return $this->hasOne(Location::class, 'id', 'end');
    }
}
