# Plugin support for Laravel applications

[![Latest Version on Packagist](https://img.shields.io/packagist/v/invisdragon/laravel-plugins.svg?style=flat-square)](https://packagist.org/packages/invisdragon/laravel-plugins)

This is a work in progress plugin framework for Laravel.

Documentation and stuff will be provided once there is something more substantial.

## Support us

Please don't support this just yet, it's not ready for production use. This will hopefully
change at some point.

## Installation

You can install the package via composer:

```bash
composer require invisdragon/laravel-plugins
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-plugins-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-plugins-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-plugins-views"
```

## Usage

```php
$invisibleDragon\LaravelPlugins = new Invisible Dragon\InvisibleDragon\LaravelPlugins();
echo $invisibleDragon\LaravelPlugins->echoPhrase('Hello, Invisible Dragon!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Joe Simpson](https://github.com/kennydude)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
