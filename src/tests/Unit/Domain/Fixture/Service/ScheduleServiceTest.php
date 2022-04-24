<?php

namespace Tests\Unit\Domain\Fixture\Service;

use App\Domain\Fixture\Entity\Fixture;
use App\Domain\Fixture\Exception\EvenTeamsInLeagueException;
use App\Domain\Fixture\Exception\FixturesAlreadyGeneratedException;
use App\Domain\Fixture\Exception\NotEnoughTeamsInLeagueException;
use App\Domain\Fixture\Service\ScheduleService;
use App\Domain\Team\Entity\Team;
use Tests\TestCase;

class ScheduleServiceTest extends TestCase
{
    public function testGenerateScheduleShouldThrowAnExceptionWhenFixturesAreAlreadyGenerated()
    {
        $teams = Team::factory()->count(4)->create();
        $fixtures = Fixture::factory()->create();
        /** @var ScheduleService $scheduleService */
        $scheduleService = app()->make(ScheduleService::class);
        $this->expectException(FixturesAlreadyGeneratedException::class);
        $scheduleService->generateSchedule($teams);
    }

    public function testGenerateScheduleShouldThrowAnExceptionWhenNumberOfTeamsAreEven()
    {
        $teams = Team::factory()->count(3)->create();
        $this->expectException(EvenTeamsInLeagueException::class);

        /** @var ScheduleService $scheduleService */
        $scheduleService = app()->make(ScheduleService::class);
        $scheduleService->generateSchedule($teams);
    }

    public function testGenerateScheduleShouldThrowAnExceptionWhenNotEnoughTeamsInLeague()
    {
        $this->expectException(NotEnoughTeamsInLeagueException::class);
        $teams = Team::factory()->count(1)->create();

        /** @var ScheduleService $scheduleService */
        $scheduleService = app()->make(ScheduleService::class);
        $scheduleService->generateSchedule($teams);
    }

    public function testGenerateSchedule()
    {
        $teams = Team::factory()->count(4)->create();

        /** @var ScheduleService $scheduleService */
        $scheduleService = app()->make(ScheduleService::class);
        $scheduleService->generateSchedule($teams);

        $this->assertDatabaseCount('fixtures', 12);
    }
}
