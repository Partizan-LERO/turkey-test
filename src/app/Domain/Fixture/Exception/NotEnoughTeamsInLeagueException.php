<?php

namespace App\Domain\Fixture\Exception;

class NotEnoughTeamsInLeagueException extends \Exception
{
    protected $message = 'Not enough teams in league';
}
