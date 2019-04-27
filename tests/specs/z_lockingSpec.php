<?php

namespace LockingSpec;

use Haijin\Instantiator\Create;
use Haijin\Instantiator\GlobalFactory;
use Haijin\Instantiator\LockedError;

$spec->describe("When locking the GlobalFactory", function () {

    $this->beforeAll(function (){
        GlobalFactory::lock();
    });

    $this->it("raises an error trying to set instantiators", function () {

        $this->expect(function () {

            GlobalFactory::set(\Sample::class, function () {
                return 123;
            });

        }) ->to() ->raise(
            LockedError::class,
            function($error) {
                $this->expect($error->getMessage()) ->to()
                    ->equal('Can not override instantiators after locking the Factory object.');
            }
        );

    });

    $this->it("raises an error trying to set singletons", function () {

        $this->expect(function () {

            GlobalFactory::setSingleton(\Sample::class, 123);

        }) ->to() ->raise(
            LockedError::class,
            function($error) {
                $this->expect($error->getMessage()) ->to()
                    ->equal('Can not override instantiators after locking the Factory object.');
            }
        );

    });

    $this->it("raises an error trying to override within a withFactoryDo closure", function () {

        $this->expect(function () {

            GlobalFactory::withFactoryDo(function ($factory) {

                $factory->set(\Sample::class, \DifferentSample::class);

            });

        }) ->to() ->raise(
            LockedError::class,
            function($error) {
                $this->expect($error->getMessage()) ->to()
                    ->equal('Can not override instantiators after locking the Factory object.');
            }
        );

    });

    $this->it("raises an error trying to re-initialize the GlobalFactory", function () {

        $this->expect(function () {

            GlobalFactory::initialize();

        }) ->to() ->raise(
            LockedError::class,
            function($error) {
                $this->expect($error->getMessage()) ->to()
                    ->equal('Can not re-initialize the GlobalFactory after locking it.');
            }
        );

    });

});
