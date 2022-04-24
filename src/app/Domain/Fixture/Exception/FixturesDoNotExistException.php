<?php

namespace App\Domain\Fixture\Exception;

class FixturesDoNotExistException extends \Exception
{
    protected $message = 'Fixtures do not exist';
}
