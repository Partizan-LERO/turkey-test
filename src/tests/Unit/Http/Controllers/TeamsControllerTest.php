<?php
namespace Tests\Unit\Http\Controllers;

use App\Domain\Team\Entity\Team;
use Tests\TestCase;

class TeamsControllerTest extends TestCase
{
    public function testGetTeams()
    {
        Team::factory()->count(4)->create();
        $response = $this->call('GET', '/api/teams');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(4, $response->decodeResponseJson()['data']);

        Team::factory()->create();
        $response = $this->call('GET', '/api/teams');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(5, $response->decodeResponseJson()['data']);

    }
}
