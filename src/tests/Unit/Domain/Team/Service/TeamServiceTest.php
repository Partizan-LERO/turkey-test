<?php

namespace Tests\Unit\Domain\Team\Service;

use App\Domain\Team\Entity\Team;
use App\Domain\Team\Enums\CurrentFormEnum;
use App\Domain\Team\Repository\TeamRepository;
use App\Domain\Team\Service\TeamService;
use Tests\TestCase;

class TeamServiceTest extends TestCase
{
    public function testGetAllTeams()
    {
        $teams = Team::factory()->count(4)->create();
        /** @var TeamRepository $teamRepository */
        $teamRepository = $this->mock(TeamRepository::class, function ($mock) use($teams) {
            $mock->shouldReceive('getAllTeams')->andReturn($teams);
        });

        $service = new TeamService($teamRepository);
        $result = $service->getAllTeams();
        $this->assertCount(4, $result);
    }

    public function testChangeCurrentForm()
    {
        $team = Team::factory()->create([
            'current_form' => CurrentFormEnum::Best->value
        ]);

        $service = app()->make(TeamService::class);

        $service->changeCurrentForm($team, true);
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'current_form' => CurrentFormEnum::Best->value
        ]);

        $service->changeCurrentForm($team, false);

        $team = Team::find($team->id);
        $this->assertTrue($team->current_form === CurrentFormEnum::Best->value || $team->current_form === CurrentFormEnum::Good->value);

        $team = Team::factory()->create([
            'current_form' => CurrentFormEnum::Bad->value
        ]);

        $service->changeCurrentForm($team, false);
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'current_form' => CurrentFormEnum::Bad->value
        ]);

        $service->changeCurrentForm($team, true);
        $team = Team::find($team->id);
        $this->assertTrue($team->current_form === CurrentFormEnum::Bad->value || $team->current_form === CurrentFormEnum::Good->value);
    }

    public function testGetMaxTour()
    {
        $service = new TeamService($this->mock(TeamRepository::class, function ($mock) {
            $mock->shouldReceive('getAllTeams')->andReturn(collect([1,2,3,4]));
        }));

        $maxTour = $service->getMaxTour();
        $this->assertEquals(6, $maxTour);
    }
}
