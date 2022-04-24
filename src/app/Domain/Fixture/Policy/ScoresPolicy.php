<?php

namespace App\Domain\Fixture\Policy;

class ScoresPolicy
{
    const GOALS_LIMIT_COEFFICIENT = 100;
    const GOAL_DIFFERENCE_COEFFICIENT = 60;

    /**
     * @param int $diff
     * @return int
     */
    public function getScoreDifference(int $diff): int {
        return ceil($diff / self::GOAL_DIFFERENCE_COEFFICIENT);
    }
}
