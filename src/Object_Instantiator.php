<?php

namespace Haijin\Instantiator;

class Object_Instantiator
{
    protected $class_name;

    public function __construct($class_name)
    {
        $this->class_name = $class_name;
    }

    public function with(...$params)
    {
        return Global_Factory::new( $this->class_name, ...$params );
    }
}