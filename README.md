# Anper\Pdo\StatementCollector

[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Build Status][ico-ga]][link-ga]

## Install

``` bash
$ composer require anper/pdo-statement-collector
```

## Usage collector

``` php
use Anper\Pdo\StatementCollector\Collector;

$pdo = new \PDO(...);

$collector = new Collector($pdo);

// pdo queries...

foreach($collector->getProfiles() as $profile) {
    var_dump($profile);
}
```

## Usage function

``` php
use Anper\Pdo\StatementCollector\Profile;
use function Anper\Pdo\StatementCollector\register_collector;

$collector = function (Profile $profile) {
    var_dump($profile);
};

register_collector($pdo, $collector);
```

## Test

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/anper/pdo-statement-collector.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-ga]: https://github.com/perevoshchikov/pdo-statement-collector/workflows/Tests/badge.svg

[link-packagist]: https://packagist.org/packages/anper/pdo-statement-collector
[link-ga]: https://github.com/perevoshchikov/pdo-statement-collector/actions
