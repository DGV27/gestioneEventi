<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'scheduled_at',
        'location',
        'max_attendees'
    ];

    public function attendees()
    {
        return $this->belongsToMany(Attendee::class, 'event_attendee');
    }

    public function isEventFull()
    {
        return $this->attendees()->count() >= $this->max_attendees;
    }
}
