<?php

namespace App\Domain\Team\Service;

use App\Domain\Team\Entity\Team;
use App\Domain\Team\Enums\CurrentFormEnum;
use App\Domain\Team\Repository\TeamRepository;
use Illuminate\Support\Collection;

class TeamService
{
    /**
     * @param TeamRepository $teamRepository
     */
    public function __construct(private readonly TeamRepository $teamRepository)
    {
    }

    /**
     * @return Collection
     */
    public function getAllTeams(): Collection
    {
        return $this->teamRepository->getAllTeams();
    }

    /**
     * @param Team $team
     * @param bool $isBetter
     * @return void
     */
    public function changeCurrentForm(Team $team, bool $isBetter): void
    {
        if ($isBetter && $team->current_form !== CurrentFormEnum::Best->value) {
            $newForm = CurrentFormEnum::cases()[rand(1,2)];

            if ($newForm->value === $team->current_form) {
                return;
            }
            $this->teamRepository->updateCurrentForm($team, $newForm->value);
        }

        if (!$isBetter && $team->current_form !== CurrentFormEnum::Bad->value) {
            $newForm = CurrentFormEnum::cases()[rand(0,1)];
            if ($newForm->value === $team->current_form) {
                return;
            }

            $this->teamRepository->updateCurrentForm($team, $newForm->value);
        }
    }

    /**
     * @return int
     */
    public function getMaxTour(): int
    {
        return ($this->getAllTeams()->count() - 1) * 2;
    }
}
