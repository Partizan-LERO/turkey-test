<?php

namespace App\Http\Resources;

use App\Domain\Fixture\Dto\StandingDto;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StandingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var StandingDto $this */
        return [
            'id' => $this->teamId(),
            'name' => $this->teamName(),
            'points' => $this->points(),
            'win_count' => $this->winCount(),
            'draw_count' => $this->drawCount(),
            'loss_count' => $this->lossCount(),
            'goals_scored' => $this->goalsScored(),
            'goals_conceded' => $this->goalsConceded(),
            'goal_difference' => $this->goalDifference(),
        ];
    }
}
