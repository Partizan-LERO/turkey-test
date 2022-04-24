<?php

namespace Tests\Unit\Domain\Fixture\Service;

use App\Domain\Fixture\Service\ScoreCalculatorService;
use Tests\TestCase;

class ScoreCalculatorServiceTest extends TestCase
{
    private ScoreCalculatorService $service;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->service = app()->make(ScoreCalculatorService::class);
        parent::__construct($name, $data, $dataName);
    }

    public function testCalculateDraw()
    {
        $total = 400;
        $score = $this->service->calculate($total, $total);

        $this->assertEquals($score->homeGoals(), $score->awayGoals());
        $this->assertLessThanOrEqual(4, $score->homeGoals());
        $this->assertGreaterThanOrEqual(0, $score->homeGoals());
    }

    public function testCalculateFirstTeamIsAWinner()
    {
        $total1 = 450;
        $total2 = 300;
        $score = $this->service->calculate($total1, $total2);

        $this->assertNotEquals($score->homeGoals(), $score->awayGoals());
        $this->assertTrue($score->homeGoals() > $score->awayGoals());
        $this->assertLessThanOrEqual(4, $score->homeGoals());
        $this->assertGreaterThanOrEqual(3, $score->homeGoals());
        $this->assertEquals(3,$score->homeGoals() - $score->awayGoals());
    }

    public function testCalculateSecondTeamIsAWinner()
    {
        $total1 = 300;
        $total2 = 450;
        $score = $this->service->calculate($total1, $total2);

        $this->assertNotEquals($score->homeGoals(), $score->awayGoals());
        $this->assertTrue($score->homeGoals() < $score->awayGoals());
        $this->assertLessThanOrEqual(4, $score->awayGoals());
        $this->assertGreaterThanOrEqual(3, $score->awayGoals());
        $this->assertEquals(3, $score->awayGoals() - $score->homeGoals());
    }
}
