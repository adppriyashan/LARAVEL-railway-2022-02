<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $fillable = ['nic', 'credit', 'from', 'to', 'location1', 'location2', 'status', 'authcode'];

    public static $status = [1 => 'Active', 2 => 'Inactive', 3 => 'Blocked', 4 => 'Deleted'];

    public function location1data()
    {
        return $this->hasOne(Location::class, 'id', 'location1');
    }

    public function location2data()
    {
        return $this->hasOne(Location::class, 'id', 'location2');
    }
}
