<?php

namespace App\Domain\Fixture\Service;

use App\Domain\Team\Entity\Team;
use App\Domain\Team\Enums\CurrentFormEnum;
use Tests\TestCase;

class CoefficientCalculatorServiceTest extends TestCase
{
    private CoefficientCalculatorService $service;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->service = app()->make(CoefficientCalculatorService::class);
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @throws \Exception
     */
    public function testCalculate() {
        $team = Team::factory()->make([
           'power' => 100,
           'supporters_strength' => 100,
           'goalkeeper_factor' => 100,
           'current_form' => CurrentFormEnum::Best->value,
        ]);

        $total = $this->service->calculate($team,true);
        $this->assertEquals(440, $total);

        $total = $this->service->calculate($team,false);
        $this->assertEquals(390, $total);

        $team->current_form = CurrentFormEnum::Bad->value;
        $total = $this->service->calculate($team, false);
        $this->assertEquals(300, $total);
    }
}
