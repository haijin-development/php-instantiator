<?php

namespace Haijin\Instantiator;

class Singleton
{
    static public function create($key, $instance)
    {
        return Global_Factory::set_singleton( $key, $instance );
    }

    static public function of($key)
    {
        return Global_Factory::singleton_of( $key );
    }
}