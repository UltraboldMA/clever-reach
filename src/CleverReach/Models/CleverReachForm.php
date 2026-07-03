<?php

namespace UltraboldMA\CleverReach\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $external_id
 * @property string $name
 * @property int|null $customer_tables_id
 * @property int $clever_reach_client_id
 * @property CleverReachClient $clever_reach_client
 *
 * @method static static create(array<string, mixed> $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static> where(string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static static updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static> with(string|array<int|string, mixed> $relations, string ...$extraRelations)
 */
class CleverReachForm extends Model
{
    protected $fillable = [
        'external_id',
        'name',
        'customer_tables_id',
        'clever_reach_client_id',
    ];

    public function clever_reach_client()
    {
        return $this->belongsTo(CleverReachClient::class);
    }
}
