# Contributing

Thank you for considering to contribute to this project.

## Submitting Pull Requests

Please submit your pull requests to the `develop` branch. That way I can deal
with it quickly and it will be scheduled for the following release.

## Tests

All new features or bug fixes should include supporting tests. To run the test
suite you need to first install the dependencies using composer:

```
$ composer install
```

Then the tests can be run using the following command:

```
$ composer test
```

This will also check for the PSR-2 Coding Standard compliance.

## Travis

Once you have submitted a pull request, Travis CI will automatically run the
tests. The tests **must** pass for the PR to be accepted.

## Coding Standard

Please stick PSR-1 and PSR-2 standards - this will be verified by Travis CI:

* https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
* https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

Also, keep the code tidy and well refactored - don't let methods get too long
or there be too many levels of indentation.

Happy coding and thank you for contributing.
