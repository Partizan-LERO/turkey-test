<?php

namespace App\Domain\Fixture\Service;

use App\Domain\Fixture\Dto\StandingDto;
use App\Domain\Fixture\Entity\Fixture;
use App\Domain\Fixture\Enums\GamePoint;
use App\Domain\Fixture\Repository\FixtureRepository;
use App\Domain\Team\Repository\TeamRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class StandingCalculatorService
{
    public function __construct(
        private  FixtureRepository $fixtureRepository,
        private  TeamRepository $teamRepository
    ) {
    }

    public function recalculateStandings(): Collection
    {
        Cache::forget("standings");
        return $this->calculateStandings();
    }

    /**
     * @return Collection
     */
    public function calculateStandings(): Collection
    {
        return Cache::rememberForever("standings", function () {
            $fixtures = $this->fixtureRepository->getAllPlayedFixtures();
            $teams = $this->teamRepository->getAllTeams();

            $standings = collect([]);

            foreach ($teams as $team) {
                $standing = new StandingDto($team->name, $team->id);

                /** @var Fixture $fixture */
                foreach ($fixtures as $fixture) {
                    if ($fixture->home_team_id == $standing->teamId()) {
                        $standing->setGoalsScored($standing->goalsScored() + $fixture->home_goals);
                        $standing->setGoalsConceded($standing->goalsConceded() + $fixture->away_goals);

                        if ($fixture->home_goals > $fixture->away_goals) {
                            $standing->setWinCount($standing->winCount() + 1);
                            $standing->setPoints($standing->points() + GamePoint::Win->value);
                        }

                        if ($fixture->home_goals === $fixture->away_goals) {
                            $standing->setDrawCount($standing->drawCount() + 1);
                            $standing->setPoints($standing->points() + GamePoint::Draw->value);
                        }

                        if ($fixture->home_goals < $fixture->away_goals) {
                            $standing->setLossCount($standing->lossCount() + 1);
                            $standing->setPoints($standing->points() + GamePoint::Loss->value);
                        }
                    }

                    if ($fixture->away_team_id == $standing->teamId()) {
                        $standing->setGoalsScored($standing->goalsScored() + $fixture->away_goals);
                        $standing->setGoalsConceded($standing->goalsConceded() + $fixture->home_goals);

                        if ($fixture->home_goals < $fixture->away_goals) {
                            $standing->setWinCount($standing->winCount() + 1);
                            $standing->setPoints($standing->points() + GamePoint::Win->value);
                        }

                        if ($fixture->home_goals === $fixture->away_goals) {
                            $standing->setDrawCount($standing->drawCount() + 1);
                            $standing->setPoints($standing->points() + GamePoint::Draw->value);
                        }

                        if ($fixture->home_goals > $fixture->away_goals) {
                            $standing->setLossCount($standing->lossCount() + 1);
                            $standing->setPoints($standing->points() + GamePoint::Loss->value);
                        }
                    }
                }

                $standings->add($standing);
            }

            return collect($standings->sortByDesc('points', SORT_REGULAR)->sortByDesc('goal_difference')->values()->all());
        });
    }
}
