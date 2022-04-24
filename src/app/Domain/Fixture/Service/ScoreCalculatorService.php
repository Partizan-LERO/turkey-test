<?php

namespace App\Domain\Fixture\Service;

use App\Domain\Fixture\Dto\ScoreDto;
use App\Domain\Fixture\Policy\ScoresPolicy;

class ScoreCalculatorService
{
    public function __construct(
        private readonly ScoresPolicy $scoresPolicy
    ) {
    }

    public function calculate(int $team1Total, int $team2Total): ScoreDto {
        if ($team1Total === $team2Total) {
            $score = rand(0, floor($team1Total / ScoresPolicy::GOALS_LIMIT_COEFFICIENT));
            return new ScoreDto($score, $score);
        }

        if ($team1Total > $team2Total) {
            $diff = $team1Total - $team2Total;
            $scoresDiff = $this->scoresPolicy->getScoreDifference($diff);

            $score1 = rand($scoresDiff, floor($team1Total / ScoresPolicy::GOALS_LIMIT_COEFFICIENT));
            $score2 = $score1 - $scoresDiff;

            return new ScoreDto($score1, $score2);
        }

        $diff = $team2Total - $team1Total;
        $scoresDiff = $this->scoresPolicy->getScoreDifference($diff);

        $score2 = rand($scoresDiff, floor($team1Total / ScoresPolicy::GOALS_LIMIT_COEFFICIENT));
        $score1 = $score2 - $scoresDiff;

        return new ScoreDto($score1, $score2);
    }
}
