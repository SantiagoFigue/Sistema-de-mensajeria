<?php

namespace Database\Factories;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente al factory.
     *
     * @var string
     */
    protected $model = Thread::class;

    /**
     * Definir el estado por defecto del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject' => $this->faker->sentence(6),
            'created_by' => User::factory(),
        ];
    }
}
