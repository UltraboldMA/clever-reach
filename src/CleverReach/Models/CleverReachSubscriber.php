<?php

namespace UltraboldMA\CleverReach\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property string|null $email
 * @property bool|null $active
 * @property Carbon|null $activated
 * @property Carbon|null $registered
 * @property array<string, mixed>|null $attributes
 * @property array<string, mixed>|null $global_attributes
 */
class CleverReachSubscriber extends Model
{
    protected $guarded = [];

    public function activated(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value == 0 ? null : now()->parse($value)->format('Y-m-d H:i:s'),
        );
    }

    public function registered(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value == 0 ? null : now()->parse($value)->format('Y-m-d H:i:s'),
        );
    }
}
