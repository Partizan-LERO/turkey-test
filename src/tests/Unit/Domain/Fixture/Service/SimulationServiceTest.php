<?php

namespace Tests\Unit\Domain\Fixture\Service;

use App\Domain\Fixture\Entity\Fixture;
use App\Domain\Fixture\Exception\FixturesDoNotExistException;
use App\Domain\Fixture\Policy\FixturePolicy;
use App\Domain\Fixture\Repository\FixtureRepository;
use App\Domain\Fixture\Service\SimulationService;
use App\Domain\Fixture\UseCase\SimulateGameUseCase;

use Illuminate\Support\Collection;
use Tests\TestCase;

class SimulationServiceTest extends TestCase
{
    public function testSimulateTourShouldThrowAnExceptionWhenFixturesAreNotGenerated()
    {
        /** @var FixtureRepository $fixtureRepository */
        $fixtureRepository = $this->mock(FixtureRepository::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->never();
        });

        /** @var FixturePolicy $fixturePolicy */
        $fixturePolicy = $this->mock(FixturePolicy::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->once()->andReturn(false);
        });

        /** @var SimulateGameUseCase $simulateGameUseCase */
        $simulateGameUseCase = $this->mock(SimulateGameUseCase::class, function ($mock) {
           $mock->shouldReceive('execute')->never();
        });

        $this->expectException(FixturesDoNotExistException::class);
        $service = new SimulationService($fixtureRepository, $simulateGameUseCase, $fixturePolicy);
        $service->simulateTour();
    }

    public function testSimulateTour()
    {
        /** @var FixtureRepository $fixtureRepository */
        $fixtureRepository = $this->mock(FixtureRepository::class);

        $fixtures = Fixture::factory()->count(2)->create([
            'is_played' => false,
            'tour' => 1
        ]);

        $fixtureRepository->shouldReceive('getFirstUnplayedTour')->once();
        $fixtureRepository->shouldReceive('getUnplayedGamesInTour')->once()->andReturn($fixtures);

        /** @var FixturePolicy $fixturePolicy */
        $fixturePolicy = $this->mock(FixturePolicy::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->once()->andReturn(true);
        });

        /** @var SimulateGameUseCase $simulateGameUseCase */
        $simulateGameUseCase = $this->mock(SimulateGameUseCase::class, function ($mock) {
            $mock->shouldReceive('execute')->twice();
        });


        $service = new SimulationService($fixtureRepository, $simulateGameUseCase, $fixturePolicy);
        $service->simulateTour();
    }

    public function testSimulateChampionshipShouldThrowAnExceptionWhenFixturesAreNotGenerated()
    {
        /** @var FixtureRepository $fixtureRepository */
        $fixtureRepository = $this->mock(FixtureRepository::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->never();
        });

        /** @var FixturePolicy $fixturePolicy */
        $fixturePolicy = $this->mock(FixturePolicy::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->once()->andReturn(false);
        });

        /** @var SimulateGameUseCase $simulateGameUseCase */
        $simulateGameUseCase = $this->mock(SimulateGameUseCase::class, function ($mock) {
            $mock->shouldReceive('execute')->never();
        });

        $this->expectException(FixturesDoNotExistException::class);
        $service = new SimulationService($fixtureRepository, $simulateGameUseCase, $fixturePolicy);
        $service->simulateChampionship();
    }

    public function testSimulateChampionship()
    {
        /** @var FixtureRepository $fixtureRepository */
        $fixtureRepository = $this->mock(FixtureRepository::class);

        $fixtures = Fixture::factory()->count(2)->create([
            'is_played' => false,
            'tour' => 1
        ]);

        $fixtureRepository->shouldReceive('getFirstUnplayedTour')->times(3);
        $fixtureRepository->shouldReceive('getMaxUnplayedTour')->once()->andReturn(1);
        $fixtureRepository->shouldReceive('getUnplayedGamesInTour')->twice()->andReturn($fixtures);

        /** @var FixturePolicy $fixturePolicy */
        $fixturePolicy = $this->mock(FixturePolicy::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->times(3)->andReturn(true);
        });

        /** @var SimulateGameUseCase $simulateGameUseCase */
        $simulateGameUseCase = $this->mock(SimulateGameUseCase::class);
        $simulateGameUseCase->shouldReceive('execute')->times(4);


        $service = new SimulationService($fixtureRepository, $simulateGameUseCase, $fixturePolicy);
        $service->simulateChampionship();
    }
}
