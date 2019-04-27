<?php

namespace SingletonSpec;

use Haijin\Instantiator\Create;
use Haijin\Instantiator\GlobalFactory;
use Haijin\Instantiator\Singleton;

$spec->describe("A Factory", function () {

    $this->beforeEach(function () {

        GlobalFactory::initialize();

    });

    $this->it("instantiates a singleton", function () {

        $instance = new \Sample();

        GlobalFactory::setSingleton(\Sample::class, $instance);

        $sameInstance = GlobalFactory::singletonOf(\Sample::class);

        $this->expect($sameInstance)->to()->be("===")->than($instance);

    });

    $this->it("instantiates a named singleton", function () {

        $instance = new \Sample();

        GlobalFactory::setSingleton('s', $instance);

        $sameInstance = GlobalFactory::singletonOf('s');

        $this->expect($sameInstance)->to()->be("===")->than($instance);

    });

    $this->it("instantiates a singleton with the dsl", function () {

        $instance = new \SampleWithParams(1, 2, 3);

        Singleton::create(\SampleWithParams::class, $instance);

        $sameInstance = Singleton::of(\SampleWithParams::class);

        $this->expect($sameInstance)->to()->be("===")->than($instance);

    });

    $this->it("instantiates a named singleton with the dsl", function () {

        $instance = new \SampleWithParams(1, 2, 3);

        Singleton::create('s', $instance);

        $sameInstance = Singleton::of('s');

        $this->expect($sameInstance)->to()->be("===")->than($instance);

    });

    $this->it("makes factory new to return a singleton", function () {

        $instance = new \Sample();

        GlobalFactory::newAsSingleton(\Sample::class, $instance);

        $sameInstance = GlobalFactory::new(\Sample::class);

        $this->expect($sameInstance)->to()->be("===")->than($instance);

    });

    $this->it("makes factory new to return a singleton with the DSL", function () {

        $instance = new \Sample();

        GlobalFactory::newAsSingleton(\Sample::class, $instance);

        $sameInstance = Create::object(\Sample::class);

        $this->expect($sameInstance)->to()->be("===")->than($instance);

    });

});