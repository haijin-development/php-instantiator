<?php

namespace Haijin\Instantiator;

class Create
{
    static public function object($class_name, ...$params)
    {
        return Global_Factory::new( $class_name, ...$params );
    }
}