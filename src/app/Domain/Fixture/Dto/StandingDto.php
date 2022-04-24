<?php

namespace App\Domain\Fixture\Dto;

class StandingDto
{
    public function __construct(
        public  string $teamName,
        public  int $teamId,
        public  int $points = 0,
        public  int $winCount = 0,
        public  int $drawCount = 0,
        public  int $lossCount = 0,
        public  int $goalsScored = 0,
        public  int $goalsConceded = 0
    ) {
    }

    /**
     * @param int $points
     */
    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    /**
     * @param int $winCount
     */
    public function setWinCount(int $winCount): void
    {
        $this->winCount = $winCount;
    }

    /**
     * @param int $drawCount
     */
    public function setDrawCount(int $drawCount): void
    {
        $this->drawCount = $drawCount;
    }

    /**
     * @param int $lossCount
     */
    public function setLossCount(int $lossCount): void
    {
        $this->lossCount = $lossCount;
    }

    /**
     * @param int $goalsScored
     */
    public function setGoalsScored(int $goalsScored): void
    {
        $this->goalsScored = $goalsScored;
    }

    /**
     * @param int $goalsConceded
     */
    public function setGoalsConceded(int $goalsConceded): void
    {
        $this->goalsConceded = $goalsConceded;
    }

    /**
     * @return string
     */
    public function teamName(): string
    {
        return $this->teamName;
    }

    /**
     * @return int
     */
    public function teamId(): int
    {
        return $this->teamId;
    }

    /**
     * @return int
     */
    public function points(): int
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function winCount(): int
    {
        return $this->winCount;
    }

    /**
     * @return int
     */
    public function drawCount(): int
    {
        return $this->drawCount;
    }

    /**
     * @return int
     */
    public function lossCount(): int
    {
        return $this->lossCount;
    }

    /**
     * @return int
     */
    public function goalsScored(): int
    {
        return $this->goalsScored;
    }

    /**
     * @return int
     */
    public function goalsConceded(): int
    {
        return $this->goalsConceded;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return (array) $this;
    }

    /**
     * @return int
     */
    public function goalDifference():int
    {
        return $this->goalsScored() - $this->goalsConceded();
    }
}
