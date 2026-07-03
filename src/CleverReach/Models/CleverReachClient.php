<?php

namespace UltraboldMA\CleverReach\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $name
 * @property string $client_id
 * @property string $client_secret
 * @property string|null $token
 * @property CarbonInterface|null $token_expiration
 *
 * @method static static create(array<string, mixed> $attributes = [])
 * @method static static findOrFail(mixed $id)
 * @method static static|null first()
 * @method static \Illuminate\Database\Eloquent\Builder<static> withCount(string|array<int|string, mixed> $relations)
 */
class CleverReachClient extends Model
{
    protected $fillable = [
        'name',
        'client_id',
        'client_secret',
        'token',
        'token_expiration',
    ];

    protected $casts = [
        'token_expiration' => 'datetime',
    ];

    protected $appends = [
        'token_valid',
    ];

    public function clever_reach_newsletters()
    {
        return $this->hasMany(CleverReachNewsletter::class);
    }

    public function tokenValid(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Arr::get($attributes, 'token_expiration')
                ? now()->parse(Arr::get($attributes, 'token_expiration'))->format('d.m.Y H:i').' Uhr'
                : '-'
        );
    }
}
