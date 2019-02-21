# Haijin Instantiator

A library to create and obtain instances of objects using a simple DSL.

[![Latest Stable Version](https://poser.pugx.org/haijin/instantiator/version)](https://packagist.org/packages/haijin/instantiator)
[![Latest Unstable Version](https://poser.pugx.org/haijin/instantiator/v/unstable)](https://packagist.org/packages/haijin/instantiator)
[![Build Status](https://travis-ci.org/haijin-development/php-instantiator.svg?branch=master)](https://travis-ci.org/haijin-development/php-instantiator)
[![License](https://poser.pugx.org/haijin/instantiator/license)](https://packagist.org/packages/haijin/instantiator)

### Version 2.0.0

If you like it a lot you may contribute by [financing](https://github.com/haijin-development/support-haijin-development) its development.

## Table of contents

1. [Installation](#c-1)
2. [Usage](#c-2)
    1. [Creating new instances](#c-2-1)
    2. [Accessing singletons](#c-2-2)
    3. [Overriding instantiators in the current process](#c-2-3)
    4. [Overriding new instantiator with a singleton](#c-2-4)
3. [Running the tests](#c-3)

<a name="c-1"></a>
## Installation

Include this library in your project `composer.json` file:

```json
{
    ...

    "require": {
        ...
        "haijin/instantiator": "^2.0",
        ...
    },

    ...
}
```

<a name="c-2"></a>
## Usage

<a name="c-2-1"></a>
### Creating new instances

Instead of using `new` to create objects use

```php
use  Haijin\Instantiator\Create;

$object = Create::a( Sample_Class::class )->with( 1, 2, 3 );
```

It is also possible not to use the DSL and avoid an object creation and a few function calls with

```php
use  Haijin\Instantiator\Create;

$object = Create::object( Sample_Class::class, 1, 2, 3 );
```

or even

```php
use  Haijin\Instantiator\Create;

$object = Global_Factory::new( Sample_Class::class, 1, 2, 3 );
```

but we strongly encourage to always use DSLs. The philosophy behind using DSLs is to optimize expressiveness, readability and simplicity. Code optimization should always be done after benchmarking real applications in their actual contexts of use.

<a name="c-2-2"></a>
### Accessing singletons

Create singleton instances of any class with

```php
use  Haijin\Instantiator\Singleton;

Singleton::create( Sample::class, new Sample( 1, 2, 3 ) );
```

and access them with

```php
use  Haijin\Instantiator\Singleton;

$object = Singleton::of( Sample::class );

// or

$object = Global_Factory::singleton_of( Sample::class );
```

Singletons can also be named

```php
use  Haijin\Instantiator\Singleton;

Singleton::create( 's', new Sample( 1, 2, 3 ) );

$object = Singleton::of( 's' );
```

<a name="c-2-3"></a>
### Overriding instantiators in the current process

It's possible to temporary change the class instantiators and singletons within the scope of a closure in the current process with

```php
use  Haijin\Instantiator\Global_Factory;
use  Haijin\Instantiator\Create;
use  Haijin\Instantiator\Singleton;

$object = Create::a( Sample::class )->with( 1, 2, 3 );

( $object instanceof Sample ) === true;

Singleton::create( 's', new Sample( 1, 2, 3 ) );


$singleton = Singleton::of( 's' );

( $singleton instanceof Sample ) === true;

Global_Factory::with_factory_do( function($factory) 
{
    // change the instantiators within the scope of this closure

    $factory->set( Sample::class, Different_Sample::class );

    Singleton::create( 's', new Different_Sample( 1, 2, 3 ) );


    $object = Create::a( Sample::class )->with( 1, 2, 3 );

    ( $object instanceof Different_Sample ) === true;


    $singleton = Singleton::of( 's' );

    ( $singleton instanceof Different_Sample ) === true;

}, $this);


// restores the previous instantiators

( $object instanceof Sample ) === true;

Singleton::create( 's', new Sample( 1, 2, 3 ) );


$singleton = Singleton::of( 's' );

( $singleton instanceof Sample ) === true;
```

That means that two different processes may override instantiators and singletons to their convenience at the same time. For instance, the instantiator class `Database` may be overriden as `MysqlDatabse` in one process and as `PostgresDatabase` in another one, but the code that uses the class `Database` can be safely shared and instantiate database objects without being aware of it and with no need to pass around factory nor container objects.

```php
use  Haijin\Instantiator\Global_Factory;
use  Haijin\Instantiator\Singleton;

public function access_data_in_mysql($connection_string)
{
    Global_Factory::with_factory_do( function($factory) use($connection_string)
    {
        Singleton::create( Database::class, new MysqlDatabase( $connection_string ) );

        $this->process_data();

    }, $this);
}

public function access_data_in_postgres($connection_string)
{
    Global_Factory::with_factory_do( function($factory) use($connection_string)
    {
        Singleton::create( Database::class, new PostgresDatabase( $connection_string ) );

        $this->process_data();

    }, $this);
}

public function process_data()
{
    $db = Singleton::of( Database::class );

    /// etc ...
}
```

<a name="c-2-4"></a>
### Overriding new instantiator with a singleton

The previous example had the problem that the code using a database needed to know that it was a singleton. That is a big assumption to do for a library.

Instead, it could instantiate the database creating a new instance each time but the code using it may override the creation of a new instance with a singleton:

```php
use  Haijin\Instantiator\Global_Factory;
use  Haijin\Instantiator\Singleton;

public function access_data_in_mysql($connection_string)
{
    Global_Factory::with_factory_do( function($factory) use($connection_string)
    {
        $factory->new_as_singleton( Database::class, new MysqlDatabase( $connection_string ) );

        $this->process_data();

    }, $this);    
}

public function access_data_in_postgres($connection_string)
{
    Global_Factory::with_factory_do( function($factory) use($connection_string)
    {
        $factory->new_as_singleton( Database::class, new PostgresDatabase( $connection_string ) );

        $this->process_data();

    }, $this);    
}

public function process_data()
{
    $db = Create::a( Database::class )->with();

    /// etc ...
}
```

Doing this allows the bottom code, such a third party library, not to assume that an object is a singleton and the top most code, such an application, to make it a singleton or not depending on its needs without the library being aware of it.

Overriding the creation of an instance by a singleton should be done only if it has no secondary effects. For instance if the singleton does not hold shared state from one function to another.

<a name="c-3"></a>
## Running the tests

```
composer specs
```