<?php

namespace App\Domain\Fixture\Service;

use App\Domain\Fixture\Exception\AllGamesPlayedException;
use App\Domain\Fixture\Repository\FixtureRepository;
use Illuminate\Support\Collection;

class FixtureService
{
    public function __construct(
        private readonly FixtureRepository $fixtureRepository,
        private readonly StandingCalculatorService $standingCalculatorService
    ) {
    }

    /**
     * @return bool
     */
    public function areFixturesExist(): bool
    {
        return $this->fixtureRepository->areFixturesGenerated();
    }

    /**
     * @throws AllGamesPlayedException
     */
    public function getCurrentTour(): int
    {
        return $this->fixtureRepository->getFirstUnplayedTour();
    }

    /**
     * @return Collection
     */
    public function getAllFixtures(): Collection
    {
        return $this->fixtureRepository->getAllFixtures();
    }

    /**
     * @param int $tour
     * @return Collection
     */
    public function getFixturesForTheTour(int $tour): Collection
    {
        return $this->fixtureRepository->getGamesInTour($tour);
    }

    public function deleteAllFixtures(): void
    {
        $this->fixtureRepository->deleteFixtures();
        $this->standingCalculatorService->recalculateStandings();
    }

}
