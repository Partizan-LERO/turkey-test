<?php
namespace Tests\Unit\Domain\Fixture\UseCase;

use App\Domain\Fixture\Exception\EvenTeamsInLeagueException;
use App\Domain\Fixture\Exception\NotEnoughTeamsInLeagueException;
use App\Domain\Fixture\UseCase\GenerateFixturesUseCase;
use Illuminate\Contracts\Container\BindingResolutionException;

class GenerateFixturesUseCaseTest extends \Tests\TestCase
{
    private GenerateFixturesUseCase $useCase;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->useCase = app()->make(GenerateFixturesUseCase::class);
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @throws NotEnoughTeamsInLeagueException
     * @throws EvenTeamsInLeagueException
     */
    public function testGenerate()
    {
        $teams = [(object)['id' => 1], (object)['id' => 2], (object)['id' => 3], (object)['id' => 4]];

        $schedule = $this->useCase->execute(collect($teams));
        $this->assertEquals(12, $schedule->count());
    }

    /**
     * @throws EvenTeamsInLeagueException
     */
    public function testGenerateWhenNotEnoughTeamsInLeague()
    {
        $teams = [(object)['id' => 1]];
        $this->expectException(NotEnoughTeamsInLeagueException::class);
        $this->useCase->execute(collect($teams));
    }

    /**
     * @throws EvenTeamsInLeagueException|NotEnoughTeamsInLeagueException
     */
    public function testGenerateWhenEvenTeamsInLeagueException()
    {
        $teams = [(object)['id' => 1], (object)['id' => 2], (object)['id' => 3]];
        $this->expectException(EvenTeamsInLeagueException::class);
        $this->useCase->execute(collect($teams));
    }
}
