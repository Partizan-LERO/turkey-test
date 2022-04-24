<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FixtureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'home_team_id' => $this->home_team_id,
            'home_team_name' => $this->homeTeam->name,
            'home_goals' => $this->home_goals,
            'away_team_id' => $this->away_team_id,
            'away_team_name' => $this->awayTeam->name,
            'away_goals' => $this->away_goals,
            'tour' => $this->tour,
            'is_played' => $this->is_played,
        ];
    }
}
