<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function index()
    {
        $attendees = Attendee::all();
        return response()->json($attendees);
    }

    public function show($id)
    {
        $attendee = Attendee::findOrFail($id);
        return response()->json($attendee);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:attendees,email'
        ]);

        $attendee = Attendee::create($validatedData);
        return response()->json($attendee, 201);
    }

    public function update(Request $request, $id)
    {
        $attendee = Attendee::findOrFail($id);

        $validatedData = $request->validate([
            'firstname' => 'sometimes|string|max:255',
            'lastname' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:attendees,email,'.$id
        ]);

        $attendee->update($validatedData);
        return response()->json($attendee);
    }

    public function destroy($id)
    {
        $attendee = Attendee::findOrFail($id);
        $attendee->delete();
        return response()->json(null, 204);
    }

    public function registraEvento(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
            'attendee_id' => 'required|exists:attendees,id'
        ]);

        $event = Event::findOrFail($validatedData['event_id']);
        $attendee = Attendee::findOrFail($validatedData['attendee_id']);

        if ($event->isEventFull()) {
            return response()->json([
                'message' => 'Evento al completo. Impossibile aggiungere altri partecipanti.',
                'max_attendees' => $event->max_attendees,
                'current_attendees' => $event->attendees()->count()
            ], 400);
        }

        if ($event->attendees()->where('attendee_id', $attendee->id)->exists()) {
            return response()->json([
                'message' => 'Partecipante già iscritto a questo evento'
            ], 400);
        }

        $event->attendees()->attach($attendee->id);

        return response()->json([
            'message' => 'Partecipazione registrata con successo',
            'event' => $event,
            'attendee' => $attendee
        ], 201);
    }

    public function rimuoviRegistrazioneDaEvento(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
            'attendee_id' => 'required|exists:attendees,id'
        ]);

        $event = Event::findOrFail($validatedData['event_id']);
        $attendee = Attendee::findOrFail($validatedData['attendee_id']);

        if (!$event->attendees()->where('attendee_id', $attendee->id)->exists()) {
            return response()->json([
                'message' => 'Partecipante non iscritto a questo evento'
            ], 400);
        }

        $event->attendees()->detach($attendee->id);

        return response()->json([
            'message' => 'Partecipazione rimossa con successo'
        ]);
    }
}
