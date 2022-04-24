<?php

namespace App\Http\Controllers;

use App\Domain\Prediction\Service\PredictionService;
use App\Domain\Team\Service\TeamService;
use App\Http\Resources\PredictionCollection;
use App\Http\Resources\PredictionResource;
use App\Http\Resources\TeamCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TeamsController extends Controller
{
    /**
     * @param TeamService $teamService
     * @return TeamCollection
     */
    public function getTeams(TeamService $teamService): TeamCollection
    {
        $teamResources = collect([]);
        $teams = $teamService->getAllTeams();

        foreach ($teams as $team) {
            $teamResources->add(new PredictionResource($team));
        }

        return new TeamCollection($teamResources);
    }
}
