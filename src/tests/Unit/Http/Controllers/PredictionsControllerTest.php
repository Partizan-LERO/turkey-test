<?php
namespace Tests\Unit\Http\Controllers;

use App\Domain\Prediction\Dto\PredictionDto;
use App\Domain\Prediction\Service\PredictionService;
use Tests\TestCase;

class PredictionsControllerTest extends TestCase
{
    public function testGetPredictions()
    {
        $response = $this->call('GET', '/api/predictions');
        $this->assertEquals(400, $response->getStatusCode());

        $this->mock(PredictionService::class, function ($mock) {
           $mock->shouldReceive('calculatePredictions')->andReturn(collect([
               new PredictionDto('Team A', 1, 100, 100, 10),
               new PredictionDto('Team B', 2, 0, 0, -10),
           ]));
        });

        $response = $this->call('GET', '/api/predictions');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $response->decodeResponseJson()['data']);

    }
}
