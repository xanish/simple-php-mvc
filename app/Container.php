<?php

namespace App;

use App\Exceptions\BindingResolutionFailedException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

class Container implements ContainerInterface
{
    protected array $bindings = [];

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     * @return mixed Entry.
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            $binding = $this->bindings[$id];

            return $binding($this);
        }

        return $this->resolve($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }

    public function register(string $id, callable|string $concrete)
    {
        $this->bindings[$id] = $concrete;
    }

    public function resolve(string $id)
    {
        $reflectionClass = new ReflectionClass($id);

        if (!$reflectionClass->isInstantiable()) {
            throw new BindingResolutionFailedException($id . ' is not instantiable');
        }

        $constructor = $reflectionClass->getConstructor();
        $params = $constructor ? $constructor->getParameters() : false;

        if (!$constructor || !$params) {
            return $reflectionClass->newInstance();
        }

        $dependencies = array_map(
            [$this, 'resolveDependency'],
            $params
        );

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    protected function resolveDependency(ReflectionParameter $dependency)
    {
        [$name, $type] = [$dependency->getName(), $dependency->getType()];

        if (!$type) {
            throw new BindingResolutionFailedException('Cannot instantiate ' . $name . ' due to missing type');
        }

        if ($type instanceof ReflectionUnionType) {
            throw new BindingResolutionFailedException('Cannot instantiate ' . $name . ' due to union types');
        }

        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            return $this->get($type->getName());
        }

        throw new BindingResolutionFailedException('Cannot instantiate ' . $name . ' due to invalid type');
    }
}
