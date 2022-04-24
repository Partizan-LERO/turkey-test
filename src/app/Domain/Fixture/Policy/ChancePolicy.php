<?php
namespace App\Domain\Fixture\Policy;

// ChancePolicy returns a coefficient of an accidental advantage for a team
class ChancePolicy
{
    public static function getChance(): int {
        return rand(0, 100);
    }
}
