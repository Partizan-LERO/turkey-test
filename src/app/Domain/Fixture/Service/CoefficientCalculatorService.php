<?php

namespace App\Domain\Fixture\Service;

use App\Domain\Fixture\Enums\FixturePlaceCoefficient;
use App\Domain\Team\Entity\Team;
use App\Domain\Team\Enums\CurrentFormCoefficientEnum;

class CoefficientCalculatorService
{
    /**
     * @throws \Exception
     */
    public function calculate(Team $team, bool $atHome): int {
        $total = $team->power + $team->supporters_strength + $team->goalkeeper_factor;
        $total += CurrentFormCoefficientEnum::get($team->current_form);
        $total += $atHome ? FixturePlaceCoefficient::Home->value : FixturePlaceCoefficient::Away->value;

        return $total;
    }
}
