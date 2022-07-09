<?php

namespace Psrearick\Containers\Services;

class ContainerItemParameters
{
    private array $parameters = [];

    public function clear() : self
    {
        $this->parameters = [];

        return $this;
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

    public function remove(string $key) : self
    {
        unset($this->parameters[$key]);

        return $this;
    }

    public function set(string $key, mixed $value) : self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function setAll(array $parameters) : self
    {
        $this->parameters = $parameters;

        return $this;
    }
}
