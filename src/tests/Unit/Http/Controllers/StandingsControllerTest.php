<?php
namespace Tests\Unit\Http\Controllers;

use App\Domain\Fixture\Dto\StandingDto;
use App\Domain\Fixture\Service\StandingCalculatorService;
use Tests\TestCase;

class StandingsControllerTest extends TestCase
{
    public function testGetStandings()
    {
        $response = $this->call('GET', '/api/standings');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(0, $response->decodeResponseJson()['data']);

        $this->mock(StandingCalculatorService::class, function ($mock) {
           $mock->shouldReceive('calculateStandings')->andReturn(collect([
               new StandingDto('Team A', 1, 15, 5, 0, 0),
               new StandingDto('Team B', 2, 12, 4, 0, 1),
           ]));
        });

        $response = $this->call('GET', '/api/standings');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $response->decodeResponseJson()['data']);

    }
}
