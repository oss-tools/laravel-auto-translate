<h6 align="center">
    <img src="https://i.ibb.co/5hhcPSH/defile-de-mode-1.png" width="300"/>
</h6>

[![Latest Version](https://img.shields.io/github/release/oss-tools/laravel-auto-translate.svg?style=flat-square)](https://github.com/oss-tools/laravel-auto-translate/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/oss-tools/laravel-auto-translate/run-tests.yml?label=tests&branch=master)
![Check & fix styling](https://github.com/oss-tools/laravel-auto-translate/workflows/Check%20&%20fix%20styling/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/oss-tools/laravel-auto-translate.svg?style=flat-square)](https://packagist.org/packages/oss-tools/laravel-auto-translate)

With this package you can translate your language files using a translator service. The package ships with a free Google Translate version, Google Translate API and Deepl.

Specify a source language and a target language and it will automatically translate your files. This is useful if you want to prototype something quickly or just a first idea of the translation for later editing. The package ships with two artisan commands. One for translating all the missing translations that are set in the source language but not in the target language. The other one for translating all source language files and overwriting everything in the target language.

## Note
This package is a detached fork of [ben182/laravel-auto-translate](https://github.com/ben182/laravel-auto-translate).

## Installation

This package can be used in Laravel 5.6 or higher and needs PHP 7.2 or higher.

You can install the package via composer:

```bash
composer require oss-tools/laravel-auto-translate
```

## Config

After installation publish the config file:

```bash
php artisan vendor:publish --provider="OSSTools\AutoTranslate\AutoTranslateServiceProvider"
```

You can specify your source language, the target language(s), the translator and the path to your language files in there.

## Translators

| Name                   | Free | File                                                        | Documentation                       | Available languages |
|------------------------|------|-------------------------------------------------------------|-------------------------------------|----------|
| Google Translate HTTP  | Yes  | OSSTools\AutoTranslate\Translators\SimpleGoogleTranslator   | /                                   | Over 100 |
| Google Cloud Translate | No   | OSSTools\AutoTranslate\Translators\GoogleCloudTranslator    | [Documentation](https://cloud.google.com/translate/) | Over 100 |
| Deepl API v2           | No   | OSSTools\AutoTranslate\Translators\DeeplTranslator          | [Documentation](https://www.deepl.com/docs-api.html) | EN, DE, FR, ES, PT, IT, NL, PL, RU |
| LibreTranslate         | Yes  | OSSTools\AutoTranslate\Translators\LibreTranslateTranslator | [Documentation](https://github.com/LibreTranslate/LibreTranslate) | AR, EN, ZH, NL, FI, FR, DE, HI, HU, ID, GA, IT, JA, KO, PL, PT, RU, ES, SV, TR, UK, VI |

If you have lots of translations to make I recommend Google Cloud Translate or Deepl. They are fast, reliable and you will not encounter any rate limiting.

## Usage

### Missing translations

Simply call the artisan missing command for translating all the translations that are set in your source language, but not in your target language:

```bash
php artisan autotrans:missing
```

E.g. you have English set as your source language. The source language has translations in auth.php:

```php
<?php

return [
    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
];
```

Your target language is German. The auth.php file has the following translations:

```php
<?php

return [
    'failed' => 'Diese Kombination aus Zugangsdaten wurde nicht in unserer Datenbank gefunden.',
];
```

The artisan missing command will then translate the missing `auth.throttle` key.

### All translations

To overwrite all your existing target language keys with the translation of the source language simply call:

```bash
php artisan autotrans:all
```

This will overwrite every single key with a translation of the equivalent source language key.

### Parameters

Sometimes you have translations like these:

```php
'welcome' => 'Welcome, :name',
```

They can be replaced with variables. When we pass these placeholders to a translator service, weird things can happen. Sometimes the placeholder comes back in upper-case letters or it has been translated. Thankfully the package will respect your variable placeholders, so they will be the same after the translation.

## Extending

You can create your own translator by creating a class that implements `\OSSTools\AutoTranslate\Translators\TranslatorInterface`. Simply reference it in your config file.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Benjamin Bortels](https://github.com/ben182)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
