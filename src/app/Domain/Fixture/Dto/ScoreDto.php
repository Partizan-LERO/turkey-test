<?php

namespace App\Domain\Fixture\Dto;

class ScoreDto
{
    public function __construct(private int $homeGoals, private int $awayGoals)
    {
    }

    /**
     * @return int
     */
    public function homeGoals(): int
    {
        return $this->homeGoals;
    }

    /**
     * @return int
     */
    public function awayGoals(): int
    {
        return $this->awayGoals;
    }
}
