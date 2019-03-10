<?php

namespace Haijin\Instantiator;

use Haijin\Dictionary;

/**
 * An global singleton of a Factory.
 */
class Global_Factory
{
    /// Class methods

    static protected $instance;

    static public function initialize()
    {
        self::$instance = new Factory();    
    }

    static public function new($key, ...$params)
    {
        return self::$instance->new($key, ...$params);
    }

    static public function set($key, $class_or_callable)
    {
        return self::$instance->set($key, $class_or_callable);
    }

    static public function singleton_of($key)
    {
        return self::$instance->singleton_of($key);
    }

    static public function set_singleton($key, $object)
    {
        return self::$instance->set_singleton($key, $object);
    }

    static public function new_as_singleton($key, $object)
    {
        return self::$instance->new_as_singleton($key, $object);
    }

    static public function with_factory_do($callable)
    {
        return self::$instance->with_factory_do($callable);
    }
}

/**
 * This kind of initialization instead of lazy initialization avoids race conditions and
 * is deterministic.
 */
Global_Factory::initialize();