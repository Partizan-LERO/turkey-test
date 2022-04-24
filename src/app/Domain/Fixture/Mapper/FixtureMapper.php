<?php
namespace App\Domain\Fixture\Mapper;

use App\Http\Resources\FixtureCollection;
use App\Http\Resources\FixtureResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class FixtureMapper
{
    /**
     * @param Collection $fixtures
     * @return JsonResponse
     */
    public function collection(Collection $fixtures): JsonResponse
    {
        $fixtureResources = [];

        foreach ($fixtures as $fixture) {
            $fixtureResources[$fixture->tour][] = new FixtureResource($fixture);
        }

        return new JsonResponse(['data' => $fixtureResources]);
    }

    /**
     * @param Collection $fixtures
     * @return FixtureCollection
     */
    public function collectionForTheTour(Collection $fixtures): FixtureCollection
    {
        $fixtureResources = collect([]);

        foreach ($fixtures as $fixture) {
            $fixtureResources->add(new FixtureResource($fixture));
        }

        return new FixtureCollection($fixtureResources);
    }
}
