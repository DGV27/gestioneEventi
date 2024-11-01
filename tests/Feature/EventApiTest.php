<?php

namespace Tests\Feature;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventApiTest extends TestCase
{
    use RefreshDatabase;

    protected $apiKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKey = config('app.api_key');
    }

    public function test_can_list_all_events()
    {
        Event::factory()->count(3)->create();

        $response = $this->withHeaders([
            'X-API-Key' => $this->apiKey
        ])->getJson('/api/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_an_event()
    {
        $eventData = [
            'title' => 'Tech Conference 2024',
            'description' => 'Annual technology conference',
            'scheduled_at' => Carbon::now()->addMonths(2)->toDateTimeString(),
            'location' => 'Milan Convention Center',
            'max_attendees' => 100
        ];

        $response = $this->withHeaders([
            'X-API-Key' => $this->apiKey
        ])->postJson('/api/events', $eventData);

        $response
            ->assertStatus(201)
            ->assertJson($eventData);

        $this->assertDatabaseHas('events', $eventData);
    }

    public function test_cannot_create_event_with_invalid_data()
    {
        $invalidEventData = [
            'title' => '',
            'scheduled_at' => 'invalid date',
            'max_attendees' => -5
        ];

        $response = $this->withHeaders([
            'X-API-Key' => $this->apiKey
        ])->postJson('/api/events', $invalidEventData);

        $response->assertStatus(422);
    }

    public function test_can_update_an_event()
    {
        $event = Event::factory()->create();

        $updatedData = [
            'title' => 'Updated Event Name',
            'max_attendees' => 200
        ];

        $response = $this->withHeaders([
            'X-API-Key' => $this->apiKey
        ])->putJson("/api/events/{$event->id}", $updatedData);

        $response
            ->assertStatus(200)
            ->assertJson($updatedData);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Event Name',
            'max_attendees' => 200
        ]);
    }

    public function test_can_delete_an_event()
    {
        $event = Event::factory()->create();

        $response = $this->withHeaders([
            'X-API-Key' => $this->apiKey
        ])->deleteJson("/api/events/{$event->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }
}
