<?php

namespace App\Domain\Fixture\Dto;

use Illuminate\Support\Collection;

class Schedule
{
    private Collection $fixtures;

    public function __construct()
    {
        $this->fixtures = new Collection();
    }

    /**
     * @param FixtureDto $fixture
     * @return void
     */
    public function addFixture(FixtureDto $fixture): void {
        $this->fixtures->add($fixture);
    }

    /**
     * @param int $teamId
     * @param int $tour
     * @return bool
     */
    public function hasGameInTour(int $teamId, int $tour): bool {
        /** @var FixtureDto $fixture */
        foreach ($this->fixtures as $fixture) {
            if ($fixture->tour() == $tour &&
                ($fixture->awayTeamId() == $teamId or $fixture->homeTeamId() == $teamId)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->getSchedule()->count();
    }

    /**
     * @return Collection|[]FixtureDto
     */
    public function getSchedule(): Collection {
        return $this->fixtures;
    }
}
