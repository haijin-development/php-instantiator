<?php

namespace Haijin\Instantiator;

class ObjectInstantiator
{
    protected $class_name;

    public function __construct($class_name)
    {
        $this->class_name = $class_name;
    }

    public function with(...$params)
    {
        return GlobalFactory::new( $this->class_name, ...$params );
    }
}