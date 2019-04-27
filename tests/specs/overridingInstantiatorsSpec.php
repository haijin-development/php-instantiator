<?php

namespace OverridingInstantiatorSpec;

use Haijin\Instantiator\Create;
use Haijin\Instantiator\GlobalFactory;

$spec->describe("A Factory", function () {

    $this->beforeEach(function () {

        GlobalFactory::initialize();

    });

    $this->it("uses the new instantiator during the overriding", function () {

        $instance = GlobalFactory::withFactoryDo(function ($factory) {

            $factory->set(\Sample::class, \DifferentSample::class);

            return Create::object(\Sample::class);

        });

        $this->expect($instance)->to()->be()->a(\DifferentSample::class);

    });

    $this->it("restores the previous instantiator after overriding", function () {

        $instance = GlobalFactory::withFactoryDo(function ($factory) {

            $factory->set(\Sample::class, \DifferentSample::class);

            return Create::object(\Sample::class);

        });

        $anotherInstance = Create::object(\Sample::class);

        $this->expect($instance)->to()->be()->a(\DifferentSample::class);
        $this->expect($anotherInstance)->to()->be()->a(\Sample::class);

    });

    $this->it("restores the top override", function () {

        GlobalFactory::withFactoryDo(function ($factory) {

            $factory->set(\Sample::class, \DifferentSample::class);

            GlobalFactory::withFactoryDo(function ($factory) {

                $factory->set(\Sample::class, \DifferentSample2::class);

                $this->instance = Create::object(\Sample::class);

            });

        });

        $this->expect($this->instance)->to()->be()->a(\DifferentSample::class);

    });

});