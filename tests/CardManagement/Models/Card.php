<?php

namespace Psrearick\Containers\Tests\CardManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psrearick\Containers\Contracts\ItemContract as Item;
use Psrearick\Containers\Tests\CardManagement\Factories\CardFactory;

class Card extends Model implements Item
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory() : CardFactory
    {
        return CardFactory::new();
    }
}
