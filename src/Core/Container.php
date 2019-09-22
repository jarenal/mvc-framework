<?php

namespace Jarenal\Core;

use Closure;
use Exception;
use ReflectionClass;

class Container
{
    protected $objects = [];

    public function __construct()
    {
        $self = $this;
        $this->add(__CLASS__, function () use ($self) {
            return $self;
        });
    }

    public function add($class, $instance = null)
    {
        if ($instance === null) {
            $instance = $class;
        }

        $this->objects[$class] = $instance;
    }

    public function get($class, $parameters = [])
    {
        try {
            if (!isset($this->objects[$class])) {
                $this->add($class);
            }
            return $this->build($this->objects[$class], $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function build($instance, $parameters)
    {
        try {
            if ($instance instanceof Closure) {
                if (is_callable($instance)) {
                    return $instance($this, $parameters);
                } else {
                    throw new Exception("$instance is not callable");
                }
            }

            $reflector = new ReflectionClass($instance);
            if (!$reflector->isInstantiable()) {
                throw new Exception("Class $instance is not instantiable");
            }

            $constructor = $reflector->getConstructor();
            if ($constructor === null) {
                return $reflector->newInstance();
            }

            $parameters = $constructor->getParameters();
            $dependencies = $this->getDependencies($parameters);

            return $reflector->newInstanceArgs($dependencies);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getDependencies($parameters)
    {
        try {
            $dependencies = [];
            foreach ($parameters as $parameter) {
                $dependency = $parameter->getClass();
                if ($dependency === null) {
                    if ($parameter->isDefaultValueAvailable()) {
                        $dependencies[] = $parameter->getDefaultValue();
                    } elseif ($parameter->allowsNull()) {
                        $dependencies[] = null;
                    } elseif ($parameter->isOptional()) {
                        $dependencies[] = null;
                    } else {
                        throw new Exception("Can't resolve class dependency {$parameter->name}");
                    }
                } else {
                    $dependencies[] = $this->get($dependency->name);
                }
            }
            return $dependencies;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
