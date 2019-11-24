# Anper\Pdo\StatementCollector

[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]


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
use function Anper\Pdo\StatementCollector\register_pdo_collector;

$collector = function (Profile $profile) {
    var_dump($profile);
};

register_pdo_collector($pdo, $collector);
```

## Test

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/anper/pdo-statement-collector.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/anper/pdo-statement-collector
