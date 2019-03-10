<?php

namespace Overriding_Instantiator_Spec;

use Haijin\Instantiator\Global_Factory;
use Haijin\Instantiator\Create;
use Haijin\Instantiator\Singleton;

$spec->describe( "A Factory", function() {

    $this->before_each( function() {

        Global_Factory::initialize();

    });

    $this->it( "uses the new instantiator during the overriding", function() {

        $instance = Global_Factory::with_factory_do( function($factory) {

            $factory->set( Sample::class, Different_Sample::class );

            return Create::a( Sample::class )->with();

        }, $this);

        $this->expect( $instance ) ->to() ->be() ->a( Different_Sample::class );

    });

    $this->it( "restores the previous instantiator after overriding", function() {

        $instance = Global_Factory::with_factory_do( function($factory) {

            $factory->set( Sample::class, Different_Sample::class );

            return Create::a( Sample::class )->with();

        }, $this);

        $another_instance = Create::a( Sample::class )->with();

        $this->expect( $instance ) ->to() ->be() ->a( Different_Sample::class );
        $this->expect( $another_instance ) ->to() ->be() ->a( Sample::class );

    });

    $this->it( "restores the top override", function() {

        Global_Factory::with_factory_do( function($factory) {

            $factory->set( Sample::class, Different_Sample::class );

                Global_Factory::with_factory_do( function($factory) {

                    $factory->set( Sample::class, Different_Sample_2::class );

                    $this->instance = Create::a( Sample::class )->with();

                }, $this);

        }, $this);

        $this->expect( $this->instance ) ->to() ->be() ->a( Different_Sample::class );

    });

});

class Sample
{
}

class Different_Sample
{
}

class Different_Sample_2
{
}
