<?php
namespace Tests\Unit\Domain\Fixture\UseCase;

use App\Domain\Fixture\Entity\Fixture;
use App\Domain\Fixture\Exception\GameAlreadyPlayedException;
use App\Domain\Fixture\Repository\FixtureRepository;
use App\Domain\Fixture\Service\CoefficientCalculatorService;
use App\Domain\Fixture\Service\ScoreCalculatorService;
use App\Domain\Fixture\Service\StandingCalculatorService;
use App\Domain\Fixture\UseCase\SimulateGameUseCase;
use App\Domain\Team\Repository\TeamRepository;
use App\Domain\Team\Service\TeamService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

class SimulateGameUseCaseTest extends TestCase
{
    private SimulateGameUseCase $useCase;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        $this->useCase = app()->make(SimulateGameUseCase::class);

        parent::setUp();
    }

   public function testExecuteShouldThrowAnExceptionWhenGameIsPlayed() {
        $fixture = Fixture::factory()->create();
        $this->expectException(GameAlreadyPlayedException::class);
        $this->useCase->execute($fixture);
   }

    public function testExecute() {
        $fixture = Fixture::factory()->create([
            'is_played' => false
        ]);

        $this->useCase->execute($fixture);

        $this->assertDatabaseHas('fixtures',[
            'id' => $fixture->id,
            'is_played' => true
        ]);
    }
}
