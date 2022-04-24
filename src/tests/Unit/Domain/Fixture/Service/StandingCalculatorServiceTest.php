<?php

namespace Tests\Unit\Domain\Fixture\Service;

use App\Domain\Fixture\Entity\Fixture;
use App\Domain\Fixture\Service\StandingCalculatorService;
use App\Domain\Team\Entity\Team;
use Tests\TestCase;

class StandingCalculatorServiceTest extends TestCase
{
    private StandingCalculatorService $service;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->service = app()->make(StandingCalculatorService::class);
        parent::__construct($name, $data, $dataName);
    }

    public function testCalculateStandings()
    {
        $teams = Team::factory()->count(2)->create();
        Fixture::factory()->create([
            'home_team_id' => $teams[0]->id,
            'away_team_id' => $teams[1]->id,
            'home_goals' => 0,
            'away_goals' => 1,
            'tour' => 1
        ]);

        Fixture::factory()->create([
            'home_team_id' => $teams[1]->id,
            'away_team_id' => $teams[0]->id,
            'home_goals' => 1,
            'away_goals' => 1,
            'tour' => 2
        ]);

        $standings = $this->service->calculateStandings();

        $this->assertEquals($standings->where('teamId', $teams[0]->id)->first()->points(), 1);
        $this->assertEquals($standings->where('teamId', $teams[1]->id)->first()->points(), 4);

        $this->assertEquals($standings[0]->teamId, $teams[1]->id);
        $this->assertEquals($standings[1]->teamId, $teams[0]->id);
    }

    public function testRecalculateStandings()
    {
        $teams = Team::factory()->count(2)->create();
        Fixture::factory()->create([
            'home_team_id' => $teams[0]->id,
            'away_team_id' => $teams[1]->id,
            'home_goals' => 0,
            'away_goals' => 1,
            'tour' => 1
        ]);

        Fixture::factory()->create([
            'home_team_id' => $teams[1]->id,
            'away_team_id' => $teams[0]->id,
            'home_goals' => 1,
            'away_goals' => 1,
            'tour' => 2
        ]);

        $standings = $this->service->recalculateStandings();

        $this->assertEquals($standings->where('teamId', $teams[0]->id)->first()->points(), 1);
        $this->assertEquals($standings->where('teamId', $teams[1]->id)->first()->points(), 4);

        $this->assertEquals($standings[0]->teamId, $teams[1]->id);
        $this->assertEquals($standings[1]->teamId, $teams[0]->id);
    }
}
