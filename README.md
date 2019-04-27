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
use Haijin\Instantiator\Create;

$object = Create::object(SampleClass::class, 1, 2, 3);
```

or

```php
use Haijin\Instantiator\Create;

$object = GlobalFactory::new(SampleClass::class, 1, 2, 3);
```

<a name="c-2-2"></a>
### Accessing singletons

Create singleton instances of any class with

```php
use Haijin\Instantiator\Singleton;

Singleton::create(Sample::class, new Sample(1, 2, 3));
```

and access them with

```php
use Haijin\Instantiator\Singleton;

$object = Singleton::of(Sample::class);

// or

$object = GlobalFactory::singletonOf(Sample::class);
```

Singletons can also be named

```php
use Haijin\Instantiator\Singleton;

Singleton::create('s', new Sample(1, 2, 3));

$object = Singleton::of('s');
```

<a name="c-2-3"></a>
### Overriding instantiators in the current process

It's possible to temporary change the class instantiators and singletons within the scope of a callable in the current process with

```php
use Haijin\Instantiator\GlobalFactory;
use Haijin\Instantiator\Create;
use Haijin\Instantiator\Singleton;

$object = Create::object(Sample::class, 1, 2, 3);

($object instanceof Sample) === true;

// change the instantiators within the scope of this closure
GlobalFactory::withFactoryDo( function($factory) 
{
    $factory->set(Sample::class, DifferentSample::class);

    $object = Create::object( Sample::class, 1, 2, 3 );

    ( $object instanceof DifferentSample ) === true;
});

// restores the previous instantiators
($object instanceof Sample) === true;
```

```php
use Haijin\Instantiator\GlobalFactory;
use Haijin\Instantiator\Create;
use Haijin\Instantiator\Singleton;

Singleton::create('s', new Sample(1, 2, 3));

$singleton = Singleton::of( 's' );

($singleton instanceof Sample) === true;

// change the instantiators within the scope of this closure
GlobalFactory::withFactoryDo( function($factory) 
{
    Singleton::create('s', new DifferentSample(1, 2, 3));

    $singleton = Singleton::of('s');

    ($singleton instanceof DifferentSample) === true;
});

$singleton = Singleton::of('s');

($singleton instanceof Sample) === true;
```


That means that two different processes may override instantiators and singletons to their convenience at the same time.
For instance, the instantiator class `Database` may be overridden as `MysqlDatabse` in one process and as `PostgresDatabase` in another one, but the code that uses the class `Database` can be safely shared and instantiate database objects without being aware of it.
It also means that in multithreaded PHP implementations different threads might override its instantiators without collisions.


```php
use Haijin\Instantiator\GlobalFactory;
use Haijin\Instantiator\Singleton;

public function accessDataInMysql($connectionString)
{
    GlobalFactory::withFactoryDo(function($factory) use($connectionString)
    {
        Singleton::create(Database::class, new MysqlDatabase($connectionString));

        $this->processData();

    });
}

public function accessDataInPostgres($connectionString)
{
    GlobalFactory::withFactoryDo(function($factory) use($connectionString)
    {
        Singleton::create(Database::class, new PostgresDatabase($connectionString));

        $this->processData();

    });
}

public function processData()
{
    $db = Singleton::of(Database::class);

    /// etc ...
}
```

<a name="c-2-4"></a>
### Overriding the `new` instantiator with a singleton

The previous example had the problem that the code using a database needed to know that it was a singleton. That is a big assumption to do for a library.

Instead, it could instantiate the database creating a new instance each time but the code using it may override the creation of a new instance with a singleton:

```php
use Haijin\Instantiator\GlobalFactory;
use Haijin\Instantiator\Singleton;

public function accessDataInMysql($connectionString)
{
    GlobalFactory::withFactoryDo(function($factory) use($connectionString) {

        $factory->newAsSingleton(Database::class, new MysqlDatabase($connectionString));

        $this->processData();

    });
}

public function accessDataInPostgres($connectionString)
{
    GlobalFactory::withFactoryDo(function($factory) use($connectionString)
    {
        $factory->newAsSingleton(Database::class, new PostgresDatabase($connectionString));

        $this->processData();

    });
}

public function processData()
{
    $db = Create::object(Database::class);

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

Or if you want to run the tests using a Docker with PHP 7.2:

```
sudo docker run -ti -v $(pwd):/home/php-instantiator --rm --name php-instantiator haijin/php-dev:7.2 bash
cd /home/php-instantiator/
composer install
composer specs
```