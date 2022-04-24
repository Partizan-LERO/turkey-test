<?php

namespace App\Domain\Team\Enums;

enum CurrentFormCoefficientEnum: int
{
    case Best = 90;
    case Good = 50;
    case Bad = 0;

    public static function get(string $currentForm):int {
        $coefficient = match ($currentForm) {
            CurrentFormEnum::Best->value => CurrentFormCoefficientEnum::Best,
            CurrentFormEnum::Good->value => CurrentFormCoefficientEnum::Good,
            CurrentFormEnum::Bad->value => CurrentFormCoefficientEnum::Bad,
            default => throw new \Exception('Unexpected match value'),
        };

        return $coefficient->value;
    }
}
