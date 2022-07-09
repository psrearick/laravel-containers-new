<?php

namespace Psrearick\Containers\Services;

class ContainerItemParameters
{
    private array $parameters = [];

    public function set(string $key, mixed $value) : void
    {
        $this->parameters[$key] = $value;
    }

    public function get(string $key) : mixed
    {
        return $this->parameters[$key] ?? null;
    }

    public function getAll() : array
    {
        return $this->parameters;
    }

    public function has(string $key) : bool
    {
        return array_key_exists($key, $this->parameters);
    }

    public function remove(string $key) : void
    {
        unset($this->parameters[$key]);
    }

    public function clear() : void
    {
        $this->parameters = [];
    }

    public function setAll(array $parameters) : void
    {
        $this->parameters = $parameters;
    }
}
