<?php

namespace App\Domain\Fixture\UseCase;

use App\Domain\Fixture\Dto\ScoreDto;
use App\Domain\Fixture\Entity\Fixture;
use App\Domain\Fixture\Exception\GameAlreadyPlayedException;
use App\Domain\Fixture\Policy\ChancePolicy;
use App\Domain\Fixture\Repository\FixtureRepository;
use App\Domain\Fixture\Service\CoefficientCalculatorService;
use App\Domain\Fixture\Service\ScoreCalculatorService;
use App\Domain\Fixture\Service\StandingCalculatorService;
use App\Domain\Team\Entity\Team;
use App\Domain\Team\Repository\TeamRepository;
use App\Domain\Team\Service\TeamService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SimulateGameUseCase
{
    /**
     * @param TeamRepository $teamRepository
     * @param FixtureRepository $fixtureRepository
     * @param CoefficientCalculatorService $coefficientCalculatorService
     * @param ScoreCalculatorService $scoreCalculatorService
     * @param StandingCalculatorService $standingCalculatorService
     */
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly TeamService $teamService,
        private readonly FixtureRepository $fixtureRepository,
        private readonly CoefficientCalculatorService $coefficientCalculatorService,
        private readonly ScoreCalculatorService    $scoreCalculatorService,
        private readonly StandingCalculatorService $standingCalculatorService
    ) {
    }

    /**
     * @param Fixture $fixture
     * @return Fixture
     * @throws GameAlreadyPlayedException
     * @throws ModelNotFoundException
     */
    public function execute(Fixture $fixture): Fixture {
        if ($fixture->is_played) {
            throw new GameAlreadyPlayedException();
        }

        $homeTeam = $this->teamRepository->getTeamById($fixture->home_team_id);
        $awayTeam = $this->teamRepository->getTeamById($fixture->away_team_id);

        $homeTeamCoefficients = $this->coefficientCalculatorService->calculate($homeTeam, true);
        $awayTeamCoefficients = $this->coefficientCalculatorService->calculate($awayTeam, false);

        if ($homeTeamCoefficients > $awayTeamCoefficients) {
            $awayTeamCoefficients += ChancePolicy::getChance();
        }
        if ($awayTeamCoefficients > $homeTeamCoefficients) {
            $homeTeamCoefficients += ChancePolicy::getChance();
        }

        $scores = $this->scoreCalculatorService->calculate($homeTeamCoefficients, $awayTeamCoefficients);

        $fixture->home_goals = $scores->homeGoals();
        $fixture->away_goals = $scores->awayGoals();
        $fixture->is_played = true;

        $this->fixtureRepository->save($fixture);

        $this->updateTeamsForm($homeTeam, $awayTeam, $scores);

        $this->standingCalculatorService->recalculateStandings();

        return $fixture;
    }

    /**
     * @param Team $homeTeam
     * @param Team $awayTeam
     * @param ScoreDto $scores
     * @return void
     */
    private function updateTeamsForm(Team $homeTeam, Team $awayTeam, ScoreDto $scores): void
    {
        if ($scores->homeGoals() > $scores->awayGoals()) {
            $this->teamService->changeCurrentForm($homeTeam, true);
            $this->teamService->changeCurrentForm($awayTeam, false);
        }

        if ($scores->homeGoals() < $scores->awayGoals()) {
            $this->teamService->changeCurrentForm($homeTeam, false);
            $this->teamService->changeCurrentForm($awayTeam, true);
        }
    }
}
