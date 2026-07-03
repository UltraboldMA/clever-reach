<?php

namespace UltraboldMA\CleverReach\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $external_id
 * @property int $clever_reach_client_id
 * @property string $name
 * @property bool $locked
 * @property bool $backup
 * @property string $receiver_info
 * @property Carbon|null $stamp
 * @property Carbon|null $last_mailing
 * @property Carbon|null $last_changed
 * @property CleverReachClient $clever_reach_client
 *
 * @method static static create(array<string, mixed> $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static> where(string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static static updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static> with(string|array<int|string, mixed> $relations, string ...$extraRelations)
 */
class CleverReachGroup extends Model
{
    protected $casts = [
        'stamp' => 'datetime:d.m.Y',
        'last_mailing' => 'datetime:d.m.Y',
        'last_changed' => 'datetime:d.m.Y',
    ];

    protected $fillable = [
        'clever_reach_client_id',
        'name',
        'locked',
        'backup',
        'receiver_info',
        'stamp',
        'last_mailing',
        'last_changed',
        'external_id',
    ];

    public function stamp(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value == 0 ? null : now()->parse($value)->format('Y-m-d H:i:s'),
        );
    }

    public function lastMailing(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value == 0 ? null : now()->parse($value)->format('Y-m-d H:i:s'),
        );
    }

    public function lastChanged(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value == 0 ? null : now()->parse($value)->format('Y-m-d H:i:s'),
        );
    }

    public function clever_reach_client()
    {
        return $this->belongsTo(CleverReachClient::class);
    }
}
