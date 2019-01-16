<?php

namespace Haijin\Instantiator;

use Haijin\Dictionary;

/**
 * An object to create or obtain instances of objects. A replacement for 'new' with additional 
 * features.
 *
 * This is the beginning of a needed and most necessary refactor.
 * It will be much more light, adaptable and clear than the injection dependecies mechanisms, 
 * allowing to do the same as them but with more ease and in the right places and times and with
 * the right contexts.
 */
class Factory
{
    /// Instance methods

    public $instantiators;
    public $singletons;

    public function __construct()
    {
        $this->instantiators = [];
        $this->singletons = [];
    }

    public function class_or_closure_for($class_name)
    {
        if( ! array_key_exists( $class_name, $this->instantiators ) ) {
            return $class_name;
        }

        return $this->instantiators[ $class_name ];
    }

    public function new($class_name, ...$params)
    {
        $class_name_or_closure = $this->class_or_closure_for($class_name);

        if( $class_name_or_closure instanceof \Closure ) {
            return $class_name_or_closure->call( $this, ...$params );
        }

        return new $class_name_or_closure( ...$params );
    }

    public function singleton($key)
    {
        return $this->singletons[ $key ];
    }

    public function set_singleton($object, $key)
    {
        $this->singletons[ $key ] = $object;

        return $this;
    }

    public function with_factory_do($closure, $binding)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        $current_instantiators = $this->instantiators;
        $current_singletons = $this->singletons;

        try {
            return $closure->call( $binding, $this );
        } finally {
            $this->instantiators = $current_instantiators;
            $this->singletons = $current_singletons;
        }
    }

    public function set($class_name, $custom_class_name)
    {
        $this->instantiators[ $class_name ] = $custom_class_name;
    }
}