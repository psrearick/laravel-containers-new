<?php

namespace Psrearick\Containers\Services;

use Psrearick\Containers\Contracts\ContainerContract;
use Psrearick\Containers\Contracts\ItemContract;

class ContainerItemRequest
{
    private string $action;

    private ContainerContract $container;

    private ?ContainerItemParameters $currentParameters;

    private ?ContainerContract $destination;

    private ItemContract $item;

    private ContainerItemParameters $parameters;

    public function __construct(
        string $action,
        ContainerContract $container,
        ItemContract $item,
        ContainerItemParameters $parameters,
        ?ContainerContract $destination,
        ?ContainerItemParameters $currentParameters
    ) {
        $this->action            = $action;
        $this->container         = $container;
        $this->item              = $item;
        $this->parameters        = $parameters;
        $this->destination       = $destination;
        $this->currentParameters = $currentParameters;
    }

    public function getAction() : string
    {
        return $this->action;
    }

    public function getContainer() : ContainerContract
    {
        return $this->container;
    }

    public function getCurrentParameters() : ?ContainerItemParameters
    {
        return $this->currentParameters;
    }

    public function getDestination() : ?ContainerContract
    {
        return $this->destination;
    }

    public function getItem() : ItemContract
    {
        return $this->item;
    }

    public function getParameters() : ContainerItemParameters
    {
        return $this->parameters;
    }
}
