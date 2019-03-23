<?php

namespace Factory_Spec;

use Haijin\Instantiator\Global_Factory;
use Haijin\Instantiator\Create;
use Haijin\Instantiator\Singleton;

$spec->describe( "A Factory", function() {

    $this->before_each( function() {

        Global_Factory::initialize();

    });

    $this->it( "instantiates an object", function() {

        $instance = Create::object( Sample::class );

        $this->expect( $instance ) ->to() ->be() ->a( Sample::class );

    });

    $this->it( "instantiates an object with params", function() {

        $instance = Create::object( Sample_With_Params::class, 1, 2, 3 );

        $this->expect( $instance ) ->to() ->be() ->a( Sample_With_Params::class );
        $this->expect( $instance->p1 ) ->to() ->equal( 1 );
        $this->expect( $instance->p2 ) ->to() ->equal( 2 );
        $this->expect( $instance->p3 ) ->to() ->equal( 3 );
 
    });

    $this->it( "instantiates an object from a callable", function() {

        Global_Factory::set( Sample::class, function() { return 123; } );

        $instance = Global_Factory::new( Sample::class );

        $this->expect( $instance ) ->to() ->equal( 123 );

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
