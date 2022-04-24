<?php

namespace App\Domain\Fixture\Service;

use App\Domain\Fixture\Exception\AllGamesPlayedException;
use App\Domain\Fixture\Exception\FixturesDoNotExistException;
use App\Domain\Fixture\Exception\GameAlreadyPlayedException;
use App\Domain\Fixture\Policy\FixturePolicy;
use App\Domain\Fixture\Repository\FixtureRepository;
use App\Domain\Fixture\UseCase\SimulateGameUseCase;
use Illuminate\Support\Collection;

class SimulationService
{
    public function __construct(
        private readonly FixtureRepository $fixtureRepository,
        private readonly SimulateGameUseCase $simulateGameUseCase,
        private readonly FixturePolicy $fixturePolicy
    ) {
    }

    /**
     * @return Collection
     * @throws FixturesDoNotExistException
     * @throws GameAlreadyPlayedException|AllGamesPlayedException
     */
    public function simulateTour(): Collection
    {
        if (!$this->fixturePolicy->areFixturesGenerated()) {
            throw new FixturesDoNotExistException();
        }

        $tour = $this->fixtureRepository->getFirstUnplayedTour();

        $games = $this->fixtureRepository->getUnplayedGamesInTour($tour);
        $results = collect([]);
        foreach ($games as $game) {
            $results->add($this->simulateGameUseCase->execute($game));
        }

        return $results;
    }

    /**
     * @throws GameAlreadyPlayedException|AllGamesPlayedException|FixturesDoNotExistException
     */
    public function simulateChampionship(): Collection
    {
        $results = collect();
        if (!$this->fixturePolicy->areFixturesGenerated()) {
            throw new FixturesDoNotExistException();
        }

        $tour = $this->fixtureRepository->getFirstUnplayedTour();
        $maxTour = $this->fixtureRepository->getMaxUnplayedTour();

        while($tour <= $maxTour) {
            $results->add($this->simulateTour());
            $tour++;
        }

        return $results;
    }
}
