<?php

namespace App\Domain\Prediction\Dto;

class PredictionDto
{
    public function __construct(
        public string $teamName,
        public int $teamId,
        public int $probability,
        public float $teamEfficiency,
        public int $goalDifference,
        public bool $isLoser = false
    ) {
    }

    /**
     * @return int
     */
    public function teamId(): int
    {
        return $this->teamId;
    }

    /**
     * @param int $teamId
     */
    public function setTeamId(int $teamId): void
    {
        $this->teamId = $teamId;
    }

    /**
     * @return int
     */
    public function probability(): int
    {
        return $this->probability;
    }

    /**
     * @param int $probability
     */
    public function setProbability(int $probability): void
    {
        $this->probability = $probability;
    }

    /**
     * @return float
     */
    public function teamEfficiency(): float
    {
        return $this->teamEfficiency;
    }

    /**
     * @param float $teamEfficiency
     */
    public function setTeamEfficiency(float $teamEfficiency): void
    {
        $this->teamEfficiency = $teamEfficiency;
    }

    public function toArray()
    {
        return (array)$this;
    }

    /**
     * @return bool
     */
    public function isLoser(): bool
    {
        return $this->isLoser;
    }

    /**
     * @param bool $isLoser
     */
    public function setIsLoser(bool $isLoser): void
    {
        $this->isLoser = $isLoser;
    }

    /**
     * @return int
     */
    public function goalDifference(): int
    {
        return $this->goalDifference;
    }

    /**
     * @param int $goalDifference
     */
    public function setGoalDifference(int $goalDifference): void
    {
        $this->goalDifference = $goalDifference;
    }

    /**
     * @return string
     */
    public function teamName(): string
    {
        return $this->teamName;
    }

    /**
     * @param string $teamName
     */
    public function setTeamName(string $teamName): void
    {
        $this->teamName = $teamName;
    }
}
