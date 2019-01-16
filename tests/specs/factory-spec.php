<?php

use  Haijin\Instantiator\Global_Factory;
use  Haijin\Instantiator\Create;
use  Haijin\Instantiator\Singleton;

$spec->describe( "A Factory", function() {

    $this->before_each( function() {

        Global_Factory::initialize();

    });

    $this->it( "instantiates an object", function() {

        $instance = Create::object( Sample::class );

        $this->expect( $instance instanceof Sample ) ->to() ->be() ->true();

    });

    $this->it( "instantiates an object with params", function() {

        $instance = Create::object( SampleWithParams::class, 1, 2, 3 );

        $this->expect( $instance instanceof SampleWithParams ) ->to() ->be() ->true();
        $this->expect( $instance->p1 ) ->to() ->equal( 1 );
        $this->expect( $instance->p2 ) ->to() ->equal( 2 );
        $this->expect( $instance->p3 ) ->to() ->equal( 3 );
 
    });

    $this->it( "instantiates an object with the dsl", function() {

        $instance = Create::a( SampleWithParams::class )->with( 1, 2, 3 );

        $this->expect( $instance instanceof SampleWithParams ) ->to() ->be() ->true();
        $this->expect( $instance->p1 ) ->to() ->equal( 1 );
        $this->expect( $instance->p2 ) ->to() ->equal( 2 );
        $this->expect( $instance->p3 ) ->to() ->equal( 3 );

    });


    $this->it( "instantiates a singleton", function() {

        Singleton::create( Sample::class )->with();

        $instance = Singleton::of( Sample::class );
        $same_instance = Singleton::of( Sample::class );

        $this->expect( $same_instance ) ->to() ->be( "===" )->than( $instance );
        $this->expect( $instance instanceof Sample ) ->to() ->be() ->true();

    });

    $this->it( "instantiates a named singleton", function() {

        Singleton::create( Sample::class, 's' )->with();

        $instance = Singleton::of( 's' );
        $same_instance = Singleton::of( 's' );

        $this->expect( $same_instance ) ->to() ->be( "===" )->than( $instance );
        $this->expect( $instance instanceof Sample ) ->to() ->be() ->true();

    });

    $this->it( "instantiates a singleton with the dsl", function() {

        Singleton::create( SampleWithParams::class )->with( 1, 2, 3 );

        $instance = Singleton::of( SampleWithParams::class );
        $same_instance = Singleton::of( SampleWithParams::class );

        $this->expect( $same_instance ) ->to() ->be( "===" )->than( $instance );
        $this->expect( ( $instance instanceof SampleWithParams ) ) ->to() ->be() ->true();
        $this->expect( $instance->p1 ) ->to() ->equal( 1 );
        $this->expect( $instance->p2 ) ->to() ->equal( 2 );
        $this->expect( $instance->p3 ) ->to() ->equal( 3 );

    });

    $this->it( "instantiates a named singleton with the dsl", function() {

        Singleton::create( SampleWithParams::class, 's' )->with( 1, 2, 3 );

        $instance = Singleton::of( 's' );
        $same_instance = Singleton::of( 's' );

        $this->expect( $same_instance ) ->to() ->be( "===" )->than( $instance );
        $this->expect( ( $instance instanceof SampleWithParams ) ) ->to() ->be() ->true();
        $this->expect( $instance->p1 ) ->to() ->equal( 1 );
        $this->expect( $instance->p2 ) ->to() ->equal( 2 );
        $this->expect( $instance->p3 ) ->to() ->equal( 3 );

    });

});

class Sample
{
}

class SampleWithParams
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
