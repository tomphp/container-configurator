# Changelog
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added
  * If `class` is left out of the config for a service, then the service name
    is assumed to be the name of the class.
  * All exceptions implement `TomPHP\ConfigServiceProvider\Exception\Exception`.
  * Support for Pimple.

### Changed
  * Exception base-classes have been updated.

### Removed
  * Custom configurable service providers (`TomPHP\ConfigServiceProvider\ConfigurableServiceProvider`).
  * Custom sub-providers.
  * `TomPHP\ConfigServiceProvider\Exception\RuntimeException`

## [0.4.0] - 2016-05-25
### Added
 * Exception thrown when no files are found when using the `fromFiles`
   constructor

### Changed
 * Config containing class names will remain as strings and not be converted to
   instances

## [0.3.3] - 2015-10-10
### Added
 * Configuring DI via the config

## [0.3.2] - 2015-09-24
### Added
 * Reading JSON config files via the `fromFiles` constructor
 * Support from braces in file globbing patterns

## [0.3.1] - 2015-09-23
### Added
 * Reading config files (PHP and JSON)

## [0.3.0] - 2015-09-23
### Added
 * `ConfigServiceProvider::fromConfig()` factory method
 * Sub providers

### Changed
 * `TomPHP\ConfigServiceProvider\InflectorConfigServiceProvider` is
   now a sub provider
 * Provider classes are marked as final

### Removed
 * `TomPHP\ConfigServiceProvider\Config` static factory

## [0.2.1] - 2015-09-21
### Added
 * Support to set up inflectors via configuration
 * `TomPHP\ConfigServiceProvider\Config` - a static class to enable easy setup.

## [0.2.0] - 2015-09-03
### Changed
 * Now depends on League Container `^2.0.2`
 * `TomPHP\ConfigServiceProvider\ConfigServiceProvider` now extends
   `League\Container\ServiceProvider\AbstractServiceProvider`

## [0.1.2] - 2014-04-12
### Added
 * Contributing guidelines
 * `composer test` to run test suite
 * Make sub-arrays accessible directly

### Changed
 * Make League Container dependency stricter (use `^` version)

## [0.1.1] - 2014-04-12
### Added
 * CHANGELOG.md
 * Homepage field to composer.json

### Fixed
 * Typo in README.md

## [0.1.0] - 2015-04-12
### Added
  * Service provider for `league/container`
  * Support for multiple levels of config
