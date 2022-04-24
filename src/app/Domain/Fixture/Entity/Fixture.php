<?php

namespace App\Domain\Fixture\Entity;

use App\Domain\Team\Entity\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $home_team_id
 * @property int $away_team_id
 * @property int $home_goals
 * @property int $away_goals
 * @property int $tour
 * @property bool $is_played
 */
class Fixture extends Model
{
    use HasFactory;

    protected $table = 'fixtures';

    public $timestamps = false;

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id', 'id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id', 'id');
    }
}
