<?php

namespace Psrearick\Containers\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $container_id
 * @property string $container_type
 * @property int $item_id
 * @property string $item_type
 * @property string $parameters
 */
class ContainerItem extends Model
{
    protected $guarded = [];
}
