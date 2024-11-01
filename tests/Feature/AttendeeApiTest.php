<?php

namespace Tests\Feature;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendeeApiTest extends TestCase
{
    use RefreshDatabase;

    protected $apiKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKey = config('app.api_key');
    }

    public function test_can_list_all_attendees()
    {
        Attendee::factory()->count(3)->create();

        $response = $this->withHeaders([
            'X-API-Key' => $this->apiKey
        ])->getJson('/api/attendees');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_an_attendee()
    {
        $attendeeData = [
            'firstname' => 'Mario',
            'lastname' => 'Rossi',
            'email' => 'mario.rossi@example.com'
        ];

        $response = $this->withHeaders([
            'X-API-Key' => $this->apiKey
        ])->postJson('/api/attendees', $attendeeData);

        $response
            ->assertStatus(201)
            ->assertJson($attendeeData);

        $this->assertDatabaseHas('attendees', $attendeeData);
    }

    public function test_cannot_create_attendee_with_duplicate_email()
    {
        Attendee::factory()->create([
            'email' => 'existing@example.com'
        ]);

        $duplicateData = [
            'firstname' => 'Duplicate',
            'lastname' => 'User',
            'email' => 'existing@example.com'
        ];

        $response = $this->withHeaders([
            'X-API-Key' => $this->apiKey
        ])->postJson('/api/attendees', $duplicateData);

        $response->assertStatus(422);
    }

    public function test_can_register_attendee_to_event()
    {
        $event = Event::factory()->create([
            'max_attendees' => 10
        ]);
        $attendee = Attendee::factory()->create();

        $response = $this->withHeaders([
            'X-API-Key' => $this->apiKey
        ])->postJson('/api/events/register', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Partecipazione registrata con successo'
            ]);
    }

    public function test_cannot_register_to_full_event()
    {
        $event = Event::factory()->create([
            'max_attendees' => 1
        ]);

        $firstAttendee = Attendee::factory()->create();
        $event->attendees()->attach($firstAttendee);

        $secondAttendee = Attendee::factory()->create();

        $response = $this->withHeaders([
            'X-API-Key' => $this->apiKey
        ])->postJson('/api/events/register', [
            'event_id' => $event->id,
            'attendee_id' => $secondAttendee->id
        ]);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Evento al completo. Impossibile aggiungere altri partecipanti.'
            ]);
    }
}
