<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Conferenza Annuale di Informatica',
                'description' => 'Evento dedicato alle ultime innovazioni tecnologiche',
                'scheduled_at' => Carbon::now()->addMonths(2),
                'location' => 'Centro Congressi Milano',
                'max_attendees' => 200
            ],
            [
                'title' => 'Hackathon Open Source',
                'description' => 'Maratona di programmazione per sviluppatori',
                'scheduled_at' => Carbon::now()->addMonths(3),
                'location' => 'Politecnico di Torino',
                'max_attendees' => 100
            ],
            [
                'title' => 'Workshop di Machine Learning',
                'description' => 'Corso pratico sull\'intelligenza artificiale',
                'scheduled_at' => Carbon::now()->addMonths(1),
                'location' => 'Università di Bologna',
                'max_attendees' => 50
            ],
            [
                'title' => 'Convegno di Cybersecurity',
                'description' => 'Strategie e tecnologie per la sicurezza informatica',
                'scheduled_at' => Carbon::now()->addMonths(4),
                'location' => 'Roma Convention Center',
                'max_attendees' => 150
            ]
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
