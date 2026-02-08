<?php

namespace Database\Factories;

use App\Models\ThreadParticipant;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadParticipantFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente al factory.
     *
     * @var string
     */
    protected $model = ThreadParticipant::class;

    /**
     * Definir el estado por defecto del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'thread_id' => Thread::factory(),
            'user_id' => User::factory(),
            'last_read_at' => $this->faker->optional(0.5)->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
