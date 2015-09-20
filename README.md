# Config Service Provider

[![Build Status](https://api.travis-ci.org/tomphp/config-service-provider.svg)](https://api.travis-ci.org/tomphp/config-service-provider)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tomphp/config-service-provider/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tomphp/config-service-provider/?branch=master)

This package contains a simple service provider for the League Of Extraordinary
Packages' [Container](https://github.com/thephpleague/container) package.

The purpose of this service provider is to take an array and add each item in
the array to the container as a value. These values can then easily be used as
dependencies of other services.

## Installation

Installation can be done easily using composer:

```
$ composer require tomphp/config-service-provider
```

## Example Usage

```php
<?php

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use TomPHP\ConfigServiceProvider\ConfigServiceProvider;

class DatabaseConnectionProvider extends AbstractServiceProvider
{
    protected $provides = [
        'database_connection',
    ];
    
    public function register()
    {
        $this->container->share('database_connection', function () {
            return new DatabaseConnection(
                $this->container->get('config.db.name'),
                $this->container->get('config.db.username'),
                $this->container->get('config.db.password')
            );
        });
    }
}

$appConfig = [
    'db' => [
        'name'     => 'example_db',
        'username' => 'dbuser',
        'password' => 'dbpass',
    ]
];

$container = new Container();

$container->addServiceProvider(new ConfigServiceProvider($appConfig));
$container->addServiceProvider(new DatabaseConnectionProvider());

$db = $container->get('database_connection');
```

* Each item in the config array is added as a separate entry into the
  container.
* Each item name is has a prefix added to it. The prefix defaults to `config`.
* If an item contains an sub-array, each of that array's are added separately
  with a name made up of the first array key, followed by a separator (defaults
  to `.`) followed by the key from the second array.

## Accessing A Whole Sub-Array

Whole sub-arrays are also made available for cases where you want them instead
of individual values. Altering the previous example, this is also possible
instead:

```php
class DatabaseConnectionProvider extends AbstractServiceProvider
{
    protected $provides = [
        'database_connection',
    ];
    
    public function register()
    {
        $this->container->share('database_connection', function () {
            /* @var array $config */
            $config = $this->container->get('config.db');
        
            return new DatabaseConnection(
                $config['name'],
                $config['username'],
                $config['password']
            );
        });
    }
}
```

## Changing the Prefix and the Separator

These can be altered via the constructor parameters for the
`TomPHP\ConfigServiceProvider\ConfigServiceProvider` class:

```php
ConfigServiceProvider::__construct(array $config, $prefix = 'config', $separator = '.')
```

## Configuring Inflectors

This package also allows the setup of
[Inflectors](http://container.thephpleague.com/inflectors/) via a configuration
array.

```php
$inflectorConfig = [
    LoggerAwareInterface::class => [
        'setLogger' => ['Some\Logger']
    ]
];

$container->addServiceProvider(new InflectorConfigServiceProvider($inflectorConfig));
```
