<?php

namespace Haijin\Instantiator;

use Haijin\Dictionary;

/**
 * An object to create or obtain instances of objects. A replacement for 'new' with additional 
 * features.
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

    public function class_or_callable_for($key)
    {
        if( ! array_key_exists( $key, $this->instantiators ) ) {
            return $key;
        }

        return $this->instantiators[ $key ];
    }

    public function set($key, $class_or_callable)
    {
        if( array_key_exists( $key, $this->instantiators ) ) {
            return;
        }

        $this->instantiators[ $key ] = $class_or_callable;
    }

    public function new($key, ...$params)
    {
        $class_name_or_callable = $this->class_or_callable_for( $key );

        if( $class_name_or_callable instanceof \Closure ) {
            return $class_name_or_callable->call( $this, ...$params );
        }

        return new $class_name_or_callable( ...$params );
    }

    public function new_as_singleton($key, $object)
    {
        return $this->set($key, function() use($object) {
            return $object;
        });
    }

    public function singleton_of($key)
    {
        return $this->singletons[ $key ];
    }

    public function set_singleton($key, $object)
    {
        $this->singletons[ $key ] = $object;

        return $this;
    }

    public function with_factory_do($callable)
    {
        $current_instantiators = $this->instantiators;
        $current_singletons = $this->singletons;

        try {

            return $callable( $this );

        } finally {

            $this->instantiators = $current_instantiators;
            $this->singletons = $current_singletons;

        }
    }
}