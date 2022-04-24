<?php
namespace App\Domain\Fixture\UseCase;

use App\Domain\Fixture\Dto\FixtureDto;
use App\Domain\Fixture\Dto\Schedule;
use App\Domain\Fixture\Exception\EvenTeamsInLeagueException;
use App\Domain\Fixture\Exception\NotEnoughTeamsInLeagueException;
use Illuminate\Support\Collection;

/**
 * GenerateFixturesUseCase generates a schedule for every team for a season.
 * Every team must play against other teams twice: home and away
 */
class GenerateFixturesUseCase
{
    /**
     * @param Collection $teams
     * @return Schedule
     * @throws EvenTeamsInLeagueException
     * @throws NotEnoughTeamsInLeagueException
     */
    public function execute(Collection $teams): Schedule {
        if ($teams->count() < 2) {
            throw new NotEnoughTeamsInLeagueException();
        }

        $rivalsCount = $teams->count() - 1;

        if ($rivalsCount % 2 == 0) {
            throw new EvenTeamsInLeagueException();
        }

        $schedule = new Schedule();
        $teamsInQueue = new \SplQueue();
        foreach ($teams as $team) {
            $teamsInQueue->enqueue($team->id);
        }

        while (!$teamsInQueue->isEmpty()) {
            $current = $teamsInQueue->dequeue();
            $rivalsQueue = clone $teamsInQueue;

            for ($i = 1; $i <= $teamsInQueue->count(); $i++) {
                $rival = $rivalsQueue->dequeue();
                $index = $i;

                if ($schedule->hasGameInTour($current, $index)) {
                    $index++;
                }

                if ($schedule->hasGameInTour($rival, $index)) {
                    $rivalsQueue->enqueue($rival);
                    $rival = $rivalsQueue->dequeue();
                }

                $schedule->addFixture(new FixtureDto($current, $rival, $index));
                $schedule->addFixture(new FixtureDto($rival, $current, $index + $rivalsCount));
            }
        }

        return $schedule;
    }
}
