<?php

namespace Haijin\Instantiator;

class Create
{
    static public function object($className, ...$params)
    {
        return GlobalFactory::new($className, ...$params);
    }
}