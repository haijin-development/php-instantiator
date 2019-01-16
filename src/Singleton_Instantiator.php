<?php

namespace Haijin\Instantiator;

class Singleton_Instantiator
{
    protected $class_name;
    protected $key;

    public function __construct($class_name, $key)
    {
        $this->class_name = $class_name;
        $this->key = $key;
    }

    public function with(...$params)
    {
        $singleton = Create::a( $this->class_name )->with( ...$params );

        Global_Factory::set_singleton( $singleton, $this->key );

        return $singleton;
    }
}