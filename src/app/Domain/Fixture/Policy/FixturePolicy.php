<?php

namespace App\Domain\Fixture\Policy;

use App\Domain\Fixture\Repository\FixtureRepository;

class FixturePolicy
{
    public function __construct(private readonly FixtureRepository $fixtureRepository)
    {
    }

    /**
     * @return bool
     */
    public function areFixturesGenerated(): bool
    {
        return $this->fixtureRepository->areFixturesGenerated();
    }
}
