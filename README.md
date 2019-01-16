# Haijin Instantiator

A library to create and obtain instances of objects using a simple DSL. A replacement for 'new' and for the infamous dependency injection mechanism with additional features.

[![Latest Stable Version](https://poser.pugx.org/haijin/instantiator/version)](https://packagist.org/packages/haijin/instantiator)
[![Latest Unstable Version](https://poser.pugx.org/haijin/instantiator/v/unstable)](https://packagist.org/packages/haijin/instantiator)
[![Build Status](https://travis-ci.org/haijin-development/php-instantiator.svg?branch=v0.0.3)](https://travis-ci.org/haijin-development/php-instantiator)
[![License](https://poser.pugx.org/haijin/instantiator/license)](https://packagist.org/packages/haijin/instantiator)

### Version 0.0.3

This library is under active development and no stable version was released yet.

If you like it a lot you may contribute by [financing](https://github.com/haijin-development/support-haijin-development) its development.

## Table of contents

1. [Installation](#c-1)
2. [Usage](#c-2)
    1. [Creating new instances](#c-2-1)
    3. [Accessing singletons](#c-2-2)
    4. [Overriding instantiators in the current thread](#c-2-3)
3. [Running the tests](#c-3)

<a name="c-1"></a>
## Installation

Include this library in your project `composer.json` file:

```json
{
    ...

    "require": {
        ...
        "haijin/instantiator": "^0.0.3",
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

$object = Create::a( SampleClass::class )->with( 1, 2, 3 );
```

It is also possible not to use the DSL and avoid an object creation and a few function calls with

```php
use  Haijin\Instantiator\Create;

$object = Create::object( SampleClass::class, 1, 2, 3 );
```

but we strongly encourage to always use DSLs. The philosophy behind every Haijin library is to optimize expressiveness, readability and simplicity. Code optimization should always be done after benchmarking real applications in their actual contexts of use.

<a name="c-2-2"></a>
### Accessing singletons

Create singleton instances of any class with

```php
use  Haijin\Instantiator\Singleton;

Singleton::create( Sample::class )->with( 1, 2, 3 );
```

and access them with

```php
use  Haijin\Instantiator\Singleton;

$object = Singleton::of( Sample::class );
```

Singletons can also be named

```php
use  Haijin\Instantiator\Singleton;

Singleton::create( Sample::class, 's' )->with( 1, 2, 3 );

$object = Singleton::of( 's' );
```

<a name="c-2-3"></a>
### Overriding instantiators in the current thread

It's possible to temporary change the class instantiators and singletons within the scope of a closure in the current thread with

```php
use  Haijin\Instantiator\Global_Factory;
use  Haijin\Instantiator\Create;
use  Haijin\Instantiator\Singleton;

$object = Create::a( Sample::class )->with( 1, 2, 3 );
( $object instanceof Sample ) === true;

Global_Factory::with_factory_do( function($factory) 
{
    $factory->set( Sample::class, DifferentSample::class );

    $object = Create::a( Sample::class )->with( 1, 2, 3 );
    ( $object instanceof DifferentSample ) === true;

    Singleton::create( DifferentSample::class )->with( 1, 2, 3 );

}, $this);


( $object instanceof Sample ) === true;
```

That means that two different threads may override instantiators and singletons to their convenience at the same time. For instance, the instantiator class `Database` may be overriden as `MysqlDatabse` in one thread and as `PostgresDatabase` in another one, but the code that uses the class `Database` can be safely shared and instantiate database objects without being aware of it and with no need to pass around factory nor container objects.

```php
use  Haijin\Instantiator\Global_Factory;
use  Haijin\Instantiator\Singleton;

public function access_data_in_mysql($connection_string)
{
    Global_Factory::with_factory_do( function($factory) use($connection_string)
    {
        Singleton::create( MysqlDatabse::class, Database::class )->with( $connection_string );

        $this->process_data();

    }, $this);    
}

public function access_data_in_postgres($connection_string)
{
    Global_Factory::with_factory_do( function($factory) use($connection_string)
    {
        Singleton::create( PostgresDatabse::class, Database::class )->with( $connection_string );

        $this->process_data();

    }, $this);    
}

public function process_data()
{
    $db = Singleton::of( Database::class );

    /// etc ...
}
```

<a name="c-3"></a>
## Running the tests

```
composer specs
```