<?php

namespace Haijin\Instantiator;

/**
 * An global singleton of a Factory.
 */
class GlobalFactory
{
    /// Class methods

    static protected $instance;

    static public function initialize()
    {
        if( self::$instance !== null && self::$instance->isLocked() ) {
            throw new LockedError('Can not re-initialize the GlobalFactory after locking it.');
        }

        self::$instance = new Factory();
    }

    static public function new($key, ...$params)
    {
        return self::$instance->new($key, ...$params);
    }

    static public function singletonOf($key)
    {
        return self::$instance->singletonOf($key);
    }

    static public function set($key, $classOrCallable)
    {
        return self::$instance->set($key, $classOrCallable);
    }

    static public function setSingleton($key, $object)
    {
        return self::$instance->setSingleton($key, $object);
    }

    static public function newAsSingleton($key, $object)
    {
        return self::$instance->newAsSingleton($key, $object);
    }

    static public function withFactoryDo($callable)
    {
        return self::$instance->withFactoryDo($callable);
    }

    static public function lock()
    {
        self::$instance->lock();
    }
}

GlobalFactory::initialize();