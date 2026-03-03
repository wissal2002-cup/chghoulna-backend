<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
     protected $fillable = [
        'user_id', 'title', 'description', 'category', 'city', 'price', 'type',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }
    use HasFactory;
}
