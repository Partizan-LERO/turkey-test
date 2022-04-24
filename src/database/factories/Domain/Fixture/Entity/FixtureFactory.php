<?php
namespace Database\Factories\Domain\Fixture\Entity;

use App\Domain\Fixture\Entity\Fixture;
use App\Domain\Team\Entity\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class FixtureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fixture::class;

    public function definition()
    {
        return [
            'home_team_id' => Team::factory()->create()->id,
            'away_team_id' => Team::factory()->create()->id,
            'home_goals' => $this->faker->numberBetween(0,5),
            'away_goals' => $this->faker->numberBetween(0,5),
            'tour' => 1,
            'is_played' => true,
        ];
    }
}
