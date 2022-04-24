<?php
namespace App\Domain\Fixture\Repository;

use App\Domain\Fixture\Entity\Fixture;
use App\Domain\Fixture\Exception\AllGamesPlayedException;
use App\Domain\Team\Entity\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FixtureRepository
{
    /**
     * @param Fixture $fixture
     * @return bool
     */
    public function save(Fixture $fixture): bool
    {
        return $fixture->save();
    }

    public function getAllFixtures(): Collection
    {
        return Fixture::query()->orderBy('tour')->get();
    }

    /**
     * @return Collection
     */
    public function getAllPlayedFixtures(): Collection
    {
        return Fixture::query()->where('is_played', true)->get();
    }

    /**
     * @param int $tour
     * @return Collection
     */
    public function getGamesInTour(int $tour): Collection
    {
        return Fixture::query()
            ->where('tour', $tour)
            ->with('homeTeam')
            ->with('awayTeam')
            ->get();
    }

    /**
     * @param int $tour
     * @return Collection
     */
    public function getUnplayedGamesInTour(int $tour): Collection
    {
        return Fixture::query()
            ->where('is_played', false)
            ->where('tour', $tour)
            ->with('homeTeam')
            ->with('awayTeam')
            ->get();
    }

    /**
     * @return mixed
     */
    public function deleteFixtures(): mixed
    {
        return Fixture::query()->delete();
    }

    /**
     * @throws AllGamesPlayedException
     */
    public function getFirstUnplayedTour(): int
    {
        /** @var Fixture $fixture */
        $fixture = Fixture::query()->where('is_played', false)->orderBy('tour')->first();

        if ($fixture != null) {
            return $fixture->tour;
        }

        throw new AllGamesPlayedException();
    }

    /**
     * @throws AllGamesPlayedException
     */
    public function getMaxUnplayedTour(): int
    {
        /** @var Fixture $fixture */
        $fixture = Fixture::query()->where('is_played', false)->orderByDesc('tour')->first();

        if ($fixture != null) {
            return $fixture->tour;
        }

        throw new AllGamesPlayedException();
    }

    /**
     * @return bool
     */
    public function areFixturesGenerated(): bool
    {
        return Fixture::query()->exists();
    }
}
