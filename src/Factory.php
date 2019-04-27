<?php

namespace Haijin\Instantiator;

/**
 * An object to create or obtain instances of objects. A replacement for 'new' with additional
 * features.
 */
class Factory
{
    protected $instantiators;
    protected $singletons;
    protected $locked;

    public function __construct()
    {
        $this->instantiators = [];
        $this->singletons = [];
        $this->locked = false;
    }

    public function getClassOrCallableFor($key)
    {
        if (!array_key_exists($key, $this->instantiators)) {
            return $key;
        }

        return $this->instantiators[$key];
    }

    public function new($key, ...$params)
    {
        $classNameOrCallable = $this->getClassOrCallableFor($key);

        if (is_callable($classNameOrCallable)) {
            return $classNameOrCallable(...$params);
        }

        return new $classNameOrCallable(...$params);
    }

    public function singletonOf($key)
    {
        return $this->singletons[$key];
    }

    public function set($key, $classOrCallable)
    {
        $this->validateLock();

        if (array_key_exists($key, $this->instantiators)) {
            return;
        }

        $this->instantiators[$key] = $classOrCallable;
    }

    public function setSingleton($key, $object)
    {
        $this->validateLock();

        $this->singletons[$key] = $object;

        return $this;
    }

    public function newAsSingleton($key, $object)
    {
        return $this->set($key, function () use ($object) {
            return $object;
        });
    }

    public function withFactoryDo($callable)
    {
        $currentInstantiators = $this->instantiators;
        $currentSingletons = $this->singletons;

        try {

            return $callable($this);

        } finally {

            $this->instantiators = $currentInstantiators;
            $this->singletons = $currentSingletons;

        }
    }

    public function lock()
    {
        $this->locked = true;
    }

    public function isLocked()
    {
        return $this->locked;
    }

    protected function validateLock(): void
    {
        if ($this->locked) {
            throw new LockedError('Can not override instantiators after locking the Factory object.');
        }
    }
}