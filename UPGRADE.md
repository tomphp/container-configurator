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
ConfigureContainer::fromFiles($container, ['config.php']);
```

Also:

```php
$container->addServiceProvider(ConfigServiceProvider::fromConfig($config);
```

Would now be replaced with:

```php
ConfigureContainer::fromArray($container, $config);
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

If you don't wish to adopt this new format, you can set the configurator up to
behave in the old way by using the following settings:

```php
[
    'services_key'   => 'di',
    'inflectors_key' => 'inflectors',
]
```
