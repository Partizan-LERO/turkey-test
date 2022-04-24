<?php

namespace App\Domain\Fixture\Exception;

class FixturesAlreadyGeneratedException extends \Exception
{
    protected $message = 'Fixtures already generated';
}
