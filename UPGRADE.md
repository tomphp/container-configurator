# Upgrade Instructions

## v0.4.\* -> v0.5.\*

There are two major changes which occurred in this update:

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
    ->withSetting('services_key', 'di')
    ->withSetting('inflectors_key', 'inflectors')
    ->to($container);
```
