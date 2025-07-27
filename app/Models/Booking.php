<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'booking_date',
        'status'
    ];

    // make the connection with user
    public function user(){
        return $this->belongsTo(User::class);
    }

    // make the connection with services
    public function service(){
        return $this->belongsTo(Service::class);
    }
}
