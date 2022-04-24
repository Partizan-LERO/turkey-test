<?php

namespace App\Http\Controllers;

use App\Domain\Prediction\Service\PredictionService;
use App\Http\Resources\PredictionCollection;
use App\Http\Resources\PredictionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PredictionsController extends Controller
{
    public function getPredictions(PredictionService $predictionService): ResourceCollection
    {
        $predictionResources = collect([]);
        $predictions = $predictionService->calculatePredictions();

        foreach ($predictions as $prediction) {
            $predictionResources->add(new PredictionResource($prediction));
        }

        return new PredictionCollection($predictionResources);
    }
}
