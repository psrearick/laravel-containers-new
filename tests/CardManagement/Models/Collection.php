<?php

namespace Psrearick\Containers\Tests\CardManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psrearick\Containers\Contracts\ContainerContract as Container;
use Psrearick\Containers\Tests\CardManagement\Factories\CollectionFactory;

class Collection extends Model implements Container
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory() : CollectionFactory
    {
        return CollectionFactory::new();
    }
}
