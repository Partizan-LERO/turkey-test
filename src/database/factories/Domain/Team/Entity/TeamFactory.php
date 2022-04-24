<?php

namespace Database\Factories\Domain\Team\Entity;

use App\Domain\Team\Entity\Team;
use App\Domain\Team\Enums\CurrentFormEnum;
use Illuminate\Database\Eloquent\Factories\Factory;


class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'power' => $this->faker->numberBetween(80,100),
            'supporters_strength' => $this->faker->numberBetween(80,100),
            'goalkeeper_factor' => $this->faker->numberBetween(80,100),
            'current_form' => CurrentFormEnum::cases()[$this->faker->numberBetween(0,2)],
        ];
    }
}
