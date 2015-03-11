Config Service Provider
=======================

[![Build Status](https://api.travis-ci.org/tomphp/config-service-provider.svg)](https://api.travis-ci.org/tomphp/config-service-provider)

This package contains a simple service provider for the League Of Extraordinary
Packages' [Container](https://github.com/thephpleague/container) package.

The purpose of this service provider is to take an array and add each item in
the array to the container as a value. These values can then easily be used for
dependencies of other services.

Installation
------------

Installation can be done easily using composer:

```
$ composer require "tomphp/config-service-provider:*"
```

Example Usage
-------------

```php
<?php

use League\Container\Container;
use TomPHP\ConfigServiceProvider\ConfigServiceProvider;

$appConfig = [
    'db' => [
        'name'     => 'example_db',
        'username' => 'dbuser',
        'password' => 'dbpass'
    ]
];

$diConfig = [
    'database_connection' => [
        'class'     => DatabaseConnection::class,
        'arguments' => [
            'config.db.example_db',
            'config.db.username',
            'config.db.password'
        ]
    ]
];

$container = new Container(['di' => $diConfig]);

$container->addServiceProvider(new ConfigServiceProvider($appConfig));

$db = $container->get('database_connection');
```

* Each item in the config array is added as a separate entry into the container.
* Each item name is has a prefix added to it. The prefix default to `config`.
* If an item contains an sub-array, each of that array's are added separately
with a name made up of the first array key, followed by a separator (defaults
to `.`) followed by the key from the second array.

Changing the Prefix and the Separator
-------------------------------------

These can be altered via the constructor parameters for the
`TomPHP\ConfigServiceProvider\ConfigServiceProvider` class:

```php
ConfigServiceProvider::__construct(array $config, $prefix = 'config', $separator = '.')
```
