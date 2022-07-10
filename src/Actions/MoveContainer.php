<?php

namespace Psrearick\Containers\Actions;

use Psrearick\Containers\Containers;
use Psrearick\Containers\Contracts\ContainerContract;

class MoveContainer
{
    public function execute(ContainerContract $child, ContainerContract $parent) : void
    {
        app(Containers::class)->unnestContainer($child);
        app(Containers::class)->nestContainer($child, $parent);
    }
}
