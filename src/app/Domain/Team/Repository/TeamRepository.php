<?php
namespace App\Domain\Team\Repository;


use App\Domain\Team\Entity\Team;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class TeamRepository
{
    /**
     * @return Collection
     */
    public function getAllTeams(): Collection
    {
        return Team::all();
    }

    /**
     * @param int $id
     * @return Team
     * @throws ModelNotFoundException
     */
    public function getTeamById(int $id):Team
    {
        return Team::findOrFail($id);
    }

    /**
     * @param Team $team
     * @param string $form
     * @return Team
     */
    public function updateCurrentForm(Team $team, string $form): Team
    {
        $team->current_form = $form;
        $team->save();

        return $team;
    }
}
