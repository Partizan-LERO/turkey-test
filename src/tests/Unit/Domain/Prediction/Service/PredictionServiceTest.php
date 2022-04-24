<?php

namespace Tests\Unit\Domain\Prediction\Service;

use App\Domain\Fixture\Dto\StandingDto;
use App\Domain\Fixture\Service\StandingCalculatorService;
use App\Domain\Prediction\Dto\PredictionDto;
use App\Domain\Prediction\Service\PredictionService;
use App\Domain\Team\Entity\Team;
use App\Domain\Team\Repository\TeamRepository;
use Tests\TestCase;

class PredictionServiceTest extends TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function testCalculatePredictions()
    {
        $teams = Team::factory()->count(4)->create();
        $standings = collect([]);

        $standings->add(new StandingDto($teams[0]->name, $teams[0]->id,9, 3, 0,0,10, 1));
        $standings->add(new StandingDto($teams[1]->name, $teams[1]->id,6, 2, 0,1,4, 2));
        $standings->add(new StandingDto($teams[2]->name, $teams[2]->id,3, 1, 0,2,2, 4));
        $standings->add(new StandingDto($teams[3]->name, $teams[3]->id,0, 0, 0,3,1, 10));

        /** @var StandingCalculatorService $standingCalculatorService */
        $standingCalculatorService = $this->mock(StandingCalculatorService::class, function ($mock) use($standings) {
            $mock->shouldReceive('recalculateStandings')->once()->andReturn($standings);
        });

        $service = new PredictionService($standingCalculatorService, new TeamRepository());
        $predictions = $service->calculatePredictions();

        $sum = 0;

        /** @var PredictionDto $prediction */
        foreach ($predictions as $prediction) {
            $sum += $prediction->probability();
        }

        $this->assertEquals(100, $sum);
        $this->assertEquals(33, $predictions->sortByDesc('probability', SORT_NATURAL)->first()->probability());
        $this->assertEquals(17, $predictions->sortByDesc('probability', SORT_NATURAL)->last()->probability());
    }

    public function testMathChampionDefined()
    {
        /** @var StandingCalculatorService $standingCalculatorService */
        $standingCalculatorService = $this->mock(StandingCalculatorService::class);
        /** @var TeamRepository $teamRepository */
        $teamRepository = $this->mock(TeamRepository::class);

        $service = new PredictionService($standingCalculatorService, $teamRepository);
        $leader = new StandingDto("Team A", 1, 15);
        $standings[] = $leader;
        $standings[] = new StandingDto("Team B", 2, 11);

        $isKnown = $service->checkIfChampionKnown(collect($standings), $leader, 0, 0);
        $this->assertTrue($isKnown);

        $isKnown = $service->checkIfChampionKnown(collect($standings), $leader, 3, 1);
        $this->assertTrue($isKnown);

        $isKnown = $service->checkIfChampionKnown(collect($standings), $leader, 6, 2);
        $this->assertFalse($isKnown);
    }

    public function testCalculateTeamEfficiency()
    {
        /** @var StandingCalculatorService $standingCalculatorService */
        $standingCalculatorService = $this->mock(StandingCalculatorService::class);
        /** @var TeamRepository $teamRepository */
        $teamRepository = $this->mock(TeamRepository::class);

        $service = new PredictionService($standingCalculatorService, $teamRepository);

        $this->assertEquals(1.0, $service->calculateTeamEfficiency(15, 3, 18));
        $this->assertEquals(0.5, $service->calculateTeamEfficiency(9, 0, 18));
        $this->assertEquals(0.75, $service->calculateTeamEfficiency(9, 0, 12));
    }

    public function testMarkLosers()
    {
        /** @var StandingCalculatorService $standingCalculatorService */
        $standingCalculatorService = $this->mock(StandingCalculatorService::class);
        /** @var TeamRepository $teamRepository */
        $teamRepository = $this->mock(TeamRepository::class);

        $leader = new StandingDto("Team A", 1, 15);
        $standings[] = $leader;
        $standings[] = new StandingDto("Team B", 2, 12);
        $standings[] = new StandingDto("Team C", 3, 9);
        $standings[] = new StandingDto("Team D", 4, 6);

        $predictions = collect([]);
        $predictions->add(new PredictionDto("Team A", 1, 0, 0.88, 10));
        $predictions->add(new PredictionDto("Team B", 2, 0, 0.75, 10));
        $predictions->add(new PredictionDto("Team C", 3, 0, 0.5, 10));
        $predictions->add(new PredictionDto("Team D", 4, 0, 0.3, 10));

        $service = new PredictionService($standingCalculatorService, $teamRepository);
        $predictions = $service->markLosers($leader, collect($standings), $predictions, 3);

        $this->assertFalse($predictions->where('teamId', 1)->first()->isLoser);
        $this->assertFalse($predictions->where('teamId', 2)->first()->isLoser);
        $this->assertTrue($predictions->where('teamId', 3)->first()->isLoser);
        $this->assertTrue($predictions->where('teamId', 4)->first()->isLoser);
    }

    public function testCalculateOverallEfficiency()
    {
        $predictions = collect([]);
        $predictions->add(new PredictionDto("Team A", 1, 0, 0.1, 10));
        $predictions->add(new PredictionDto("Team B", 2, 0, 0.2, 10));
        $predictions->add(new PredictionDto("Team C", 2, 0, 100, 10, true));

        /** @var StandingCalculatorService $standingCalculatorService */
        $standingCalculatorService = $this->mock(StandingCalculatorService::class);
        /** @var TeamRepository $teamRepository */
        $teamRepository = $this->mock(TeamRepository::class);

        $service = new PredictionService($standingCalculatorService, $teamRepository);
        $result = $service->calculateOverallEfficiency($predictions);
        $this->assertEquals(0.3, $result);
    }
}
