# Mpesa Mozambique PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/karson/mpesa-php-sdk.svg?style=flat-square)](https://packagist.org/packages/karson/mpesa-php-sdk)
[![Build Status](https://img.shields.io/travis/karson/mpesa-php-sdk/master.svg?style=flat-square)](https://travis-ci.org/karson/mpesa-php-sdk)
[![Quality Score](https://img.shields.io/scrutinizer/g/karson/mpesa-php-sdk.svg?style=flat-square)](https://scrutinizer-ci.com/g/karson/mpesa-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/karson/mpesa-php-sdk.svg?style=flat-square)](https://packagist.org/packages/karson/mpesa-php-sdk)

This package seeks to help php developers implement the various Mpesa APIs without much hustle. It is based on the REST API whose documentation is available on https://developer.mpesa.vm.co.mz/.

## Installation

You can install the package via composer:

```bash
composer require karson/mpesa-php-sdk
```

## Usage

``` php
// Set the consumer key and consumer secret as follows
$mpesa = new \Karson\MpesaPhpSdk\Mpesa();
$mpesa->setApiKey('your api key');
$mpesa->setPublicKey('your public key');
$mpesa->setEnv('test');// 'live' production environment 

//This creates transaction between an M-Pesa short code to a phone number registered on M-Pesa.

$result = $mpesa->c2b($invoice_id, $phone_number, $amount, $reference_id, $shortcode);
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email karson@turbohost.co instead of using the issue tracker.

## Credits

- [Karson Adam](https://github.com/karson)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
