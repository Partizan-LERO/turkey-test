<?php

namespace App\Http\Controllers;

use App\Domain\Fixture\Exception\AllGamesPlayedException;
use App\Domain\Fixture\Exception\EvenTeamsInLeagueException;
use App\Domain\Fixture\Exception\FixturesAlreadyGeneratedException;
use App\Domain\Fixture\Exception\FixturesDoNotExistException;
use App\Domain\Fixture\Exception\GameAlreadyPlayedException;
use App\Domain\Fixture\Exception\NotEnoughTeamsInLeagueException;
use App\Domain\Fixture\Mapper\FixtureMapper;
use App\Domain\Fixture\Service\FixtureService;
use App\Domain\Fixture\Service\ScheduleService;
use App\Domain\Fixture\Service\SimulationService;
use App\Domain\Team\Repository\TeamRepository;
use App\Domain\Team\Service\TeamService;
use App\Http\Resources\FixtureCollection;
use Illuminate\Http\JsonResponse;

class FixturesController extends Controller
{

    /**
     * @param FixtureService $fixtureService
     * @return JsonResponse
     */
    public function fixturesExist(
        FixtureService $fixtureService
    ): JsonResponse {
        return new JsonResponse(['data' => ['exist' => $fixtureService->areFixturesExist()]]);
    }

    /**
     * @param FixtureService $fixtureService
     * @param TeamService $teamService
     * @return JsonResponse
     */
    public function getCurrentTour(
        FixtureService $fixtureService,
        TeamService $teamService
    ): JsonResponse {
        try {
            $currentTour = $fixtureService->getCurrentTour();
            return new JsonResponse(['data' => ['tour' => $currentTour]]);
        } catch (AllGamesPlayedException) {
            if (!$fixtureService->areFixturesExist()) {
                $tour = 1;
            } else {
                $tour = $teamService->getMaxTour();
            }
            return new JsonResponse(['data' => ['tour' => $tour ]]);
        }

    }

    /**
     * @throws EvenTeamsInLeagueException
     * @throws NotEnoughTeamsInLeagueException|FixturesAlreadyGeneratedException
     */
    public function generate(
        TeamRepository $teamRepository,
        ScheduleService $scheduleService
    ): JsonResponse
    {
        $teams = $teamRepository->getAllTeams();
        $scheduleService->generateSchedule($teams);

        return new JsonResponse(null, 201);
    }

    /**
     * @throws GameAlreadyPlayedException|FixturesDoNotExistException|AllGamesPlayedException
     */
    public function simulate(
        SimulationService $simulationService
    ): JsonResponse
    {
        $simulationService->simulateTour();
        return new JsonResponse(null, 201);
    }

    /**
     * @throws GameAlreadyPlayedException|AllGamesPlayedException|FixturesDoNotExistException
     */
    public function simulateChampionship(
        SimulationService $simulationService
    ): JsonResponse
    {
        $simulationService->simulateChampionship();
        return new JsonResponse(null, 201);
    }

    /**
     * @param FixtureService $fixtureService
     * @return JsonResponse
     */
    public function resetFixtures(
        FixtureService $fixtureService
    ): JsonResponse
    {
        $fixtureService->deleteAllFixtures();

        return new JsonResponse(null, 204);
    }

    /**
     * @param FixtureService $fixtureService
     * @param FixtureMapper $fixtureMapper
     * @return JsonResponse
     */
    public function getGeneratedFixtures(
        FixtureService $fixtureService,
        FixtureMapper $fixtureMapper
    ): JsonResponse
    {
        $fixtures = $fixtureService->getAllFixtures();

        return $fixtureMapper->collection($fixtures);
    }

    public function getFixtures(
        int $tour,
        FixtureService $fixtureService,
        FixtureMapper $fixtureMapper
    ): FixtureCollection
    {
        $fixtures = $fixtureService->getFixturesForTheTour($tour);

        return $fixtureMapper->collectionForTheTour($fixtures);
    }
}
