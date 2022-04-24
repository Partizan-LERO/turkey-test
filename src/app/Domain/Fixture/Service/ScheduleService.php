<?php

namespace App\Domain\Fixture\Service;

use App\Domain\Fixture\Dto\FixtureDto;
use App\Domain\Fixture\Entity\Fixture;
use App\Domain\Fixture\Exception\EvenTeamsInLeagueException;
use App\Domain\Fixture\Exception\FixturesAlreadyGeneratedException;
use App\Domain\Fixture\Exception\NotEnoughTeamsInLeagueException;
use App\Domain\Fixture\Policy\FixturePolicy;
use App\Domain\Fixture\Repository\FixtureRepository;
use App\Domain\Fixture\UseCase\GenerateFixturesUseCase;
use Illuminate\Support\Collection;

class ScheduleService
{
    /**
     * @param GenerateFixturesUseCase $generateFixturesUseCase
     * @param FixtureRepository $fixtureRepository
     * @param FixturePolicy $fixturePolicy
     */
    public function __construct(
        private readonly GenerateFixturesUseCase $generateFixturesUseCase,
        private readonly FixtureRepository $fixtureRepository,
        private readonly FixturePolicy $fixturePolicy
    ) {
    }

    /**
     * @throws EvenTeamsInLeagueException
     * @throws NotEnoughTeamsInLeagueException|FixturesAlreadyGeneratedException
     */
    public function generateSchedule(Collection $teams): void
    {
        if ($this->fixturePolicy->areFixturesGenerated()) {
            throw new FixturesAlreadyGeneratedException();
        }

        $schedule = $this->generateFixturesUseCase->execute($teams);
        $games = $schedule->getSchedule();

        /** @var FixtureDto $game */
        foreach ($games as $game) {
            $fixture = new Fixture();
            $fixture->home_team_id = $game->homeTeamId();
            $fixture->away_team_id = $game->awayTeamId();
            $fixture->tour = $game->tour();

            $this->fixtureRepository->save($fixture);
        }
    }
}
