# Omnipay: Square

**Square driver for the Omnipay PHP payment processing library**

[![Build Status](https://app.travis-ci.com/Alofoxx/omnipay-square.svg?branch=master)](https://app.travis-ci.com/Alofoxx/omnipay-square)
[![Latest Stable Version](https://poser.pugx.org/alofoxx/omnipay-square/version.png)](https://packagist.org/packages/alofoxx/omnipay-square)
[![Total Downloads](https://poser.pugx.org/alofoxx/omnipay-square/d/total.png)](https://packagist.org/packages/alofoxx/omnipay-square)
[![License](https://poser.pugx.org/alofoxx/omnipay-square/license)](https://packagist.org/packages/alofoxx/omnipay-square)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 7.2+. This package implements Square support for Omnipay.

_Note that this package is only tested with PHP 8.0 and above._
## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "alofoxx/omnipay-square": "~3.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Square

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/alofoxx/omnipay-square/issues),
or better yet, fork the library and submit a pull request.

## Older Versions
This package is a fork of the original [omnipay-square](https://github.com/transportersio/omnipay-square) package. 
The original package is no longer maintained and this repo has merged in the [community made](https://github.com/alofoxx/omnipay-square/graphs/contributors) PRs and updates. 
This package is a continuation of the original package with the following changes:
- Updated to work with the latest version of Omnipay
- Updated to work with the latest version of Square
- Updated to work with the latest version of PHP