<?php

namespace UltraboldMA\CleverReach\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $clever_reach_client_id
 * @property string $name
 * @property string $key
 * @property int|null $form_id
 * @property int $group_id
 * @property string $language
 * @property bool $double_opt_in
 * @property array<int, string>|null $cl_attributes
 * @property array<int, string>|null $cl_global_attributes
 * @property CleverReachClient $clever_reach_client
 * @property CleverReachForm|null $clever_reach_form
 * @property CleverReachGroup $clever_reach_group
 *
 * @method static static create(array<string, mixed> $attributes = [])
 * @method static static findOrFail(mixed $id)
 * @method static \Illuminate\Database\Eloquent\Builder<static> where(string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static> with(string|array<int|string, mixed> $relations, string ...$extraRelations)
 */
class CleverReachNewsletter extends Model
{
    protected $fillable = [
        'clever_reach_client_id',
        'name',
        'key',
        'form_id',
        'group_id',
        'language',
        'double_opt_in',
        'cl_attributes',
        'cl_global_attributes',
    ];

    protected $casts = [
        'cl_attributes' => 'array',
        'cl_global_attributes' => 'array',
    ];

    public function clever_reach_client()
    {
        return $this->belongsTo(CleverReachClient::class);
    }

    public function clever_reach_form()
    {
        return $this->belongsTo(CleverReachForm::class, 'form_id');
    }

    public function clever_reach_group()
    {
        return $this->belongsTo(CleverReachGroup::class, 'group_id');
    }
}
