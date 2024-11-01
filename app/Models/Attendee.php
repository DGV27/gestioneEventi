<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'email'
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_attendee');
    }

}
