<?php

namespace Singleton_Spec;

use  Haijin\Instantiator\Global_Factory;
use  Haijin\Instantiator\Create;
use  Haijin\Instantiator\Singleton;

$spec->describe( "A Factory", function() {

    $this->before_each( function() {

        Global_Factory::initialize();

    });

    $this->it( "instantiates a singleton", function() {

        $instance = new Sample();

        Global_Factory::set_singleton( Sample::class, $instance );

        $same_instance = Global_Factory::singleton_of( Sample::class );

        $this->expect( $same_instance ) ->to() ->be( "===" )->than( $instance );

    });

    $this->it( "instantiates a named singleton", function() {

        $instance = new Sample();

        Global_Factory::set_singleton( 's', $instance );

        $same_instance = Global_Factory::singleton_of( 's' );

        $this->expect( $same_instance ) ->to() ->be( "===" )->than( $instance );

    });

    $this->it( "instantiates a singleton with the dsl", function() {

        $instance = new Sample_With_Params( 1, 2, 3 );

        Singleton::create( Sample_With_Params::class, $instance );

        $same_instance = Singleton::of( Sample_With_Params::class );

        $this->expect( $same_instance ) ->to() ->be( "===" )->than( $instance );

    });

    $this->it( "instantiates a named singleton with the dsl", function() {

        $instance = new Sample_With_Params( 1, 2, 3 );

        Singleton::create( 's', $instance );

        $same_instance = Singleton::of( 's' );

        $this->expect( $same_instance ) ->to() ->be( "===" )->than( $instance );

    });

    $this->it( "makes factory new to return a singleton", function() {

        $instance = new Sample();

        Global_Factory::new_as_singleton( Sample::class, $instance );

        $same_instance = Global_Factory::new( Sample::class );

        $this->expect( $same_instance ) ->to() ->be( "===" )->than( $instance );

    });

    $this->it( "makes factory new to return a singleton with the DSL", function() {

        $instance = new Sample();

        Global_Factory::new_as_singleton( Sample::class, $instance );

        $same_instance = Create::a( Sample::class )->with();

        $this->expect( $same_instance ) ->to() ->be( "===" )->than( $instance );

    });

});

class Sample
{
}

class Sample_With_Params
{
    public $p1;
    public $p2;
    public $p3;

    public function __construct($p1, $p2, $p3)
    {
        $this->p1 = $p1;
        $this->p2 = $p2;
        $this->p3 = $p3;
    }
}
