<?php

namespace Haijin\Instantiator;

class Singleton
{
    static public function create($key, $instance)
    {
        return GlobalFactory::setSingleton($key, $instance);
    }

    static public function of($key)
    {
        return GlobalFactory::singletonOf($key);
    }
}