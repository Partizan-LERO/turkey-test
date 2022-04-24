<?php

namespace App\Domain\Fixture\Enums;

enum GamePoint: int
{
    case Win = 3;
    case Draw = 1;
    case Loss = 0;
}
