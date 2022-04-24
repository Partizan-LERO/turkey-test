<?php

namespace App\Domain\Team\Entity;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @property string $name
 * @property int $power
 * @property int $supporters_strength
 * @property int $goalkeeper_factor
 * @property string $current_form
 */
class Team extends Model
{
    use HasFactory;

    public $timestamps = false;

}
