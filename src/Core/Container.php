<?php
declare(strict_types=1);

namespace Jarenal\Core;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionParameter;

/**
 * Class Container
 * @package Jarenal\Core
 */
class Container
{
    /**
     * @var array
     */
    protected $factories = [];

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $self = $this;
        $this->add(__CLASS__, function () use ($self) {
            return $self;
        });
    }

    /**
     * @return array
     */
    public function getFactories(): array
    {
        return $this->factories;
    }

    /**
     * @param string $class
     * @param Closure|null $closure
     */
    public function add(string $class, Closure $closure = null): void
    {
        if ($closure === null) {
            $closure = $class;
        }

        $this->factories[$class] = $closure;
    }

    /**
     * @param string $class
     * @param array $parameters
     * @return object
     * @throws Exception
     */
    public function get(string $class, array $parameters = []): object
    {
        try {
            if (!isset($this->factories[$class])) {
                $this->add($class);
            }
            return $this->build($this->factories[$class], $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param $class
     * @param array $parameters
     * @return object
     * @throws Exception
     */
    private function build($class, array $parameters = []): object
    {
        try {
            if ($class instanceof Closure) {
                return call_user_func_array($class, $parameters);
            }

            $reflector = new ReflectionClass($class);
            if (!$reflector->isInstantiable()) {
                throw new Exception("Class $class is not instantiable");
            }

            $constructor = $reflector->getConstructor();
            if ($constructor === null) {
                return $reflector->newInstance();
            }

            $constructorParameters = $constructor->getParameters();
            $mergedParameters = array_replace($constructorParameters, $parameters);
            $dependencies = $this->getDependencies($mergedParameters);

            return $reflector->newInstanceArgs($dependencies);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $parameters
     * @return array
     * @throws Exception
     */
    private function getDependencies(array $parameters): array
    {
        try {
            $dependencies = [];
            foreach ($parameters as $parameter) {
                if ($parameter instanceof ReflectionParameter) {
                    $dependency = $parameter->getClass();
                    if ($dependency === null) {
                        if ($parameter->isDefaultValueAvailable()) {
                            $dependencies[] = $parameter->getDefaultValue();
                        } elseif ($parameter->allowsNull()) {
                            $dependencies[] = null;
                        } else {
                            throw new Exception("Can't resolve class dependency {$parameter->name}");
                        }
                    } else {
                        $dependencies[] = $this->get($dependency->name);
                    }
                } else {
                    $dependencies[] = $parameter;
                }
            }
            return $dependencies;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
