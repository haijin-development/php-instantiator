<?php

namespace Haijin\Instantiator;

class Singleton
{
    static public function create($class_name, $key = null)
    {
        if( $key === null ) {
            $key = $class_name;
        }

        return new SingletonInstantiator( $class_name, $key );
    }

    static public function of($key)
    {
        return GlobalFactory::singleton( $key );
    }
}