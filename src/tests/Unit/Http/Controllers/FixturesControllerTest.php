<?php
namespace Tests\Unit\Http\Controllers;


use App\Domain\Fixture\Entity\Fixture;
use App\Domain\Fixture\Policy\FixturePolicy;
use App\Domain\Fixture\Service\FixtureService;
use App\Domain\Team\Entity\Team;
use App\Domain\Team\Repository\TeamRepository;
use Tests\TestCase;

class FixturesControllerTest extends TestCase
{
    public function testFixturesExist()
    {
        $response = $this->call('GET', '/api/fixtures/exist');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertFalse($response->decodeResponseJson()['data']['exist']);

        $this->mock(FixtureService::class, function ($mock) {
            $mock->shouldReceive('areFixturesExist')->andReturn(true);
        });

        $response = $this->call('GET', '/api/fixtures/exist');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->decodeResponseJson()['data']['exist']);
    }

    public function testGetCurrentTour()
    {
        $this->mock(FixtureService::class, function ($mock) {
            $mock->shouldReceive('getCurrentTour')->andReturn(1);
        });

        $response = $this->call('GET', '/api/current-tour');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1,$response->decodeResponseJson()['data']['tour']);

        $this->mock(FixtureService::class, function ($mock) {
            $mock->shouldReceive('getCurrentTour')->andReturn(2);
        });

        $response = $this->call('GET', '/api/current-tour');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, $response->decodeResponseJson()['data']['tour']);
    }

    public function testGenerate()
    {
        $this->mock(TeamRepository::class, function ($mock) {
            $mock->shouldReceive('getAllTeams')->andReturn(collect([Team::factory()->create()]));
        });

        $response = $this->call('POST', '/api/fixtures/generate');
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Not enough teams in league', $response->decodeResponseJson()['message']);


        $this->mock(TeamRepository::class, function ($mock) {
            $mock->shouldReceive('getAllTeams')->andReturn(collect([
                Team::factory()->create(),
                Team::factory()->create(),
                Team::factory()->create(),
            ]));
        });

        $response = $this->call('POST', '/api/fixtures/generate');
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Even teams in league', $response->decodeResponseJson()['message']);


        $this->mock(TeamRepository::class, function ($mock) {
            $mock->shouldReceive('getAllTeams')->andReturn(collect([
                Team::factory()->create(),
                Team::factory()->create(),
            ]));
        });

        $this->mock(FixturePolicy::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->andReturn(true);
        });

        $response = $this->call('POST', '/api/fixtures/generate');
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Fixtures already generated', $response->decodeResponseJson()['message']);


        $this->mock(TeamRepository::class, function ($mock) {
            $mock->shouldReceive('getAllTeams')->andReturn(collect([
                Team::factory()->create(),
                Team::factory()->create()
            ]));
        });

        $this->mock(FixturePolicy::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->andReturn(false);
        });

        $response = $this->call('POST', '/api/fixtures/generate');
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testResetFixtures()
    {
        $response = $this->call('DELETE', '/api/fixtures/reset');
        $this->assertEquals(204, $response->getStatusCode());
        $response->assertNoContent();
    }

    public function testSimulate()
    {
        $response = $this->call('POST', '/api/fixtures/simulate/tour');
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Fixtures do not exist', $response->decodeResponseJson()['message']);

        Fixture::factory()->create([
            'is_played' => false
        ]);

        $response = $this->call('POST', '/api/fixtures/simulate/tour');
        $this->assertEquals(201, $response->getStatusCode());

        $this->mock(FixturePolicy::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->andReturn(true);
        });

        $response = $this->call('POST', '/api/fixtures/simulate/tour');
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('All games played', $response->decodeResponseJson()['message']);
    }

    public function testSimulateChampionship()
    {
        $this->mock(FixturePolicy::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->andReturn(true);
        });

        $response = $this->call('POST', '/api/fixtures/simulate');
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('All games played', $response->decodeResponseJson()['message']);

        Fixture::factory()->create([
            'is_played' => false
        ]);

        $response = $this->call('POST', '/api/fixtures/simulate');
        $this->assertEquals(201, $response->getStatusCode());

        $this->mock(FixturePolicy::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->andReturn(true);
        });

        $response = $this->call('POST', '/api/fixtures/simulate');
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('All games played', $response->decodeResponseJson()['message']);
    }

    public function testGetGeneratedFixtures()
    {
        $this->mock(FixtureService::class, function ($mock) {
            $mock->shouldReceive('getAllFixtures')->andReturn(collect([
                Fixture::factory()->create(['tour' => 1]),
                Fixture::factory()->create(['tour' => 2]),
            ]));
        });

        $response = $this->call('GET', '/api/fixtures');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2,count($response->decodeResponseJson()['data']));
    }

    public function testGetFixtures()
    {
        $this->mock(FixtureService::class, function ($mock) {
            $mock->shouldReceive('getFixturesForTheTour')->andReturn(collect(
                Fixture::factory()->count(2)->create(['tour' => 1])
            ));
        });

        $response = $this->call('GET', '/api/fixtures/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($response->decodeResponseJson()['data']));
    }
}
