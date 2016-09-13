# Upgrade Instructions

## v0.4.\* -> v0.5.\*

There are three major changes which occurred in this update:

### Package Renamed

This package is now called **Container Configurator**. It's not installed with
the command:

```
composer require tomphp/container-configurator
```

For existing projects, you can fix this by running:

```
composer remove tomphp/config-service-provider && composer require tomphp/container-configurator
```

To require it in your project you need to:

```
use TomPHP\ContainerConfigurator\Configurator;
```

### New API

The API had a major rewrite in v0.5.0. Rather than creating a service provider
like this:

```php
$container->addServiceProvider(ConfigServiceProvider::fromFile(['config.php']);
```

You now configure the container like this:

```php
Configurator::apply()
    ->configFromFiles('config/*.inc.php')
    ->to($container);
```

If you had multiple file pattern matches, you can chain more `configFromFiles`
calls like so:

```php
Configurator::apply()
    ->configFromFiles('*.global.php')
    ->configFromFiles('*.local.php')
    ->to($container);
```

Also:

```php
$container->addServiceProvider(ConfigServiceProvider::fromConfig($config);
```

Would now be replaced with:

```php
Configurator::apply()
    ->configFromArray($config)
    ->to($container);
```

### Default DI Config Has Changed Structure

There's been some changes to where the service and inflector config go by
default. Before v0.5.0 the config would look like this:

```php
$config = [
  'di' => [
      // Config for services goes here
  ],
  'inflectors' => [
      // Config for inflectors goes here
  ],
];
```

By default, v0.5.0 now has this format:

```php
$config = [
    'di' => [
      'services' => [
          // Config for services goes here
      ],
      'inflectors' => [
          // Config for inflectors goes here
      ],
    ],
];
```

If you don't wish to adopt this new format, you can set the Configurator up to
behave in the old way by using the following settings:

```php
Configurator::apply()
    ->configFromArray($config)
    ->withSetting(Configurator::SETTING_SERVICES_KEY, 'di')
    ->withSetting(Configurator::SETTING_INFLECTORS_KEY, 'inflectors')
    ->to($container);
```
