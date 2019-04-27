<?php

namespace FactorySpec;

use Haijin\Instantiator\Create;
use Haijin\Instantiator\GlobalFactory;

$spec->describe("A Factory", function () {

    $this->it("instantiates an object", function () {

        $instance = Create::object(\Sample::class);

        $this->expect($instance)->to()->be()->a(\Sample::class);

    });

    $this->it("instantiates an object with params", function () {

        $instance = Create::object(\SampleWithParams::class, 1, 2, 3);

        $this->expect($instance)->to()->be()->a(\SampleWithParams::class);
        $this->expect($instance->p1)->to()->equal(1);
        $this->expect($instance->p2)->to()->equal(2);
        $this->expect($instance->p3)->to()->equal(3);

    });

    $this->it("instantiates an object from a callable", function () {

        GlobalFactory::set(\Sample::class, function () {
            return 123;
        });

        $instance = GlobalFactory::new(\Sample::class);

        $this->expect($instance)->to()->equal(123);

    });

});