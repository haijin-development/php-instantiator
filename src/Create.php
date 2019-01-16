<?php

namespace Haijin\Instantiator;

class Create
{
    static public function object($class_name, ...$params)
    {
        return Global_Factory::new( $class_name, ...$params );
    }

    static public function a($class_name)
    {
        return new Object_Instantiator( $class_name );
    }

    static public function an($class_name)
    {
        return new Object_Instantiator( $class_name );
    }
}