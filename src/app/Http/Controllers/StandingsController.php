<?php

namespace App\Http\Controllers;

use App\Domain\Fixture\Service\StandingCalculatorService;
use App\Http\Resources\StandingCollection;
use App\Http\Resources\StandingResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StandingsController extends Controller
{
    /**
     * @param StandingCalculatorService $standingCalculatorService
     * @return ResourceCollection
     */
    public function getStandings(StandingCalculatorService $standingCalculatorService): ResourceCollection
    {
        $standings = $standingCalculatorService->calculateStandings();
        $standingResources = collect([]);

        foreach ($standings as $standing) {
            $standingResources->add(new StandingResource($standing));
        }

        return new StandingCollection($standingResources);
    }
}
