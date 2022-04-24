<?php
namespace App\Domain\Prediction\Service;

use App\Domain\Fixture\Dto\StandingDto;
use App\Domain\Fixture\Enums\GamePoint;
use App\Domain\Fixture\Service\StandingCalculatorService;
use App\Domain\Prediction\Dto\PredictionDto;
use App\Domain\Team\Repository\TeamRepository;
use Illuminate\Support\Collection;
use voku\helper\ASCII;

class PredictionService
{
    public function __construct(
        private readonly StandingCalculatorService $standingCalculatorService,
        private readonly TeamRepository $teamRepository
    ) {
    }

    public function calculatePredictions(): Collection
    {
        $predictions = collect([]);
        $standings = $this->standingCalculatorService->recalculateStandings();

        $teamsCount = $this->teamRepository->getAllTeams()->count();
        $totalGamesCount = ($teamsCount - 1) * 2;

        /** @var StandingDto $standing */
        $standing = $standings[0];

        $gamesPlayed = $standing->winCount() + $standing->drawCount() + $standing->lossCount();
        $gamesLeft = $totalGamesCount - $gamesPlayed;
        $maxPointsLeft = $gamesLeft * GamePoint::Win->value;
        $maxChampionshipPoints = $totalGamesCount * GamePoint::Win->value;
        $maxFactPoints = 0;
        $leader = null;

        foreach ($standings as $standing) {
            $prediction = new PredictionDto(
                $standing->teamName(),
                $standing->teamId,
                0,
                $this->calculateTeamEfficiency($standing->points(), $maxPointsLeft, $maxChampionshipPoints),
                $standing->goalDifference(),
            );

            if ($standing->points() > $maxFactPoints ||
                ($standing->points() === $maxFactPoints && $standing->goalDifference() > $leader->goalDifference())) {
                $maxFactPoints = $standing->points();
                $leader = $standing;
            }

            $predictions->add($prediction);
        }

        $isChampionKnown = $this->checkIfChampionKnown($standings, $leader, $maxPointsLeft, $gamesLeft);
        if ($isChampionKnown) {
            /** @var PredictionDto $prediction */
            foreach ($predictions as $prediction) {
                if ($prediction->teamId() === $leader->teamId()) {
                    $prediction->setProbability(100);
                    break;
                }
            }

            return $predictions;
        }

        $predictions = $this->markLosers($leader, $standings, $predictions, $maxPointsLeft);
        $overallEfficiency = $this->calculateOverallEfficiency($predictions);

        /** @var PredictionDto $prediction */
        foreach ($predictions as $prediction) {
            if ($prediction->isLoser()) {
                continue;
            }

            if ($overallEfficiency != 0) {
                $prediction->setProbability(round($prediction->teamEfficiency() / $overallEfficiency * 100));
            }
        }

        return $predictions;
    }

    /**
     * @param Collection $standings
     * @param StandingDto $leader
     * @param int $maxPointsLeft
     * @param int $gamesLeft
     * @return bool
     */
    public function checkIfChampionKnown(
        Collection $standings,
        StandingDto $leader,
        int $maxPointsLeft,
        int $gamesLeft
    ): bool {
        if ($gamesLeft === 0) {
            return true;
        }

        /** @var StandingDto $standing */
        foreach ($standings as $standing) {
            if ($leader->teamId() === $standing->teamId()) {
                continue;
            }

            if ($leader->points() - ($standing->points() + $maxPointsLeft) <= 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int $teamPoints
     * @param int $maxPointsLeft
     * @param int $maxPoints
     * @return float
     */
    public function calculateTeamEfficiency(int $teamPoints, int $maxPointsLeft, int $maxPoints): float
    {
        return ($teamPoints + $maxPointsLeft) / $maxPoints;
    }

    /**
     * @param StandingDto $leader
     * @param Collection $standings
     * @param Collection $predictions
     * @param int $maxPointsLeft
     * @return Collection
     */
    public function markLosers(
        StandingDto $leader,
        Collection $standings,
        Collection $predictions,
        int $maxPointsLeft
    ): Collection {
        /** @var StandingDto $standing */
        foreach ($standings as $standing) {
            if ($standing->teamId() == $leader->teamId()) {
                continue;
            }

            if ($leader->points() - ($standing->points() + $maxPointsLeft) > 0) {
                /** @var PredictionDto $prediction */
                $prediction = $predictions->where('teamId', $standing->teamId())->first();
                $prediction->setIsLoser(true);
                $prediction->setProbability(0);
                $prediction->setTeamEfficiency(0);
            }
        }
        return $predictions;
    }

    /**
     * @param Collection $predictions
     * @return float
     */
    public function calculateOverallEfficiency(Collection $predictions): float
    {
        $overallEfficiency = 0;

        foreach ($predictions as $prediction) {
            if (!$prediction->isLoser()) {
                $overallEfficiency += $prediction->teamEfficiency();
            }
        }

        return $overallEfficiency;
    }
}
