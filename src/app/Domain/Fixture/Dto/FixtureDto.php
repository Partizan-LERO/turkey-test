<?php

namespace App\Domain\Fixture\Dto;


class FixtureDto
{
    /**
     * @param int $homeTeamId
     * @param int $awayTeamId
     * @param int $tour
     */
    public function __construct(
        private  int $homeTeamId,
        private  int $awayTeamId,
        private  int $tour,
    ) {
    }

    public function get(): FixtureDto
    {
        return $this;
    }

    /**
     * @return int
     */
    public function tour(): int {
        return $this->tour;
    }

    /**
     * @return int
     */
    public function homeTeamId(): int {
        return $this->homeTeamId;
    }

    public function awayTeamId(): int {
        return $this->awayTeamId;
    }
}
