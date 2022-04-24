<?php

namespace App\Domain\Fixture\Exception;

class GameAlreadyPlayedException extends \Exception
{
    protected $message = 'Game Already played';
}
