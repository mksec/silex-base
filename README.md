# Silex Base-Application

[![](https://img.shields.io/travis/mksec/silex-base/master.svg?style=flat-square)](https://travis-ci.org/mksec/silex-base)
[![](https://img.shields.io/codecov/c/github/mksec/silex-base.svg?style=flat-square)](https://codecov.io/github/mksec/silex-base?branch=master)
[![](https://img.shields.io/github/issues-raw/mksec/silex-base.svg?style=flat-square)](https://github.com/mksec/silex-base/issues)
[![](http://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

Basic [Silex](http://silex.sensiolabs.org/) application, configured to use
common providers.


## About

Most of the applications developed by mksec use the same common infrastructure
with Silex: There's a class inherited from the Silex Application class, which
configures the error- and exception handlers. Although the providers depend on
the individual applications, some of them are common for all of them.

This repository provides some classes providing the basic infrastructure, that
may be used as base classes for the applications to build, making them more
simple, as common code doesn't need to be repeated.


## Classes

* `BaseApplication`

  The `BaseApplication` class is a simple Silex Application with the Error- and
  ExceptionHandler registered. It may be used for simple applications without an
  UI (e.g. APIs).

* `BaseUiApplication`

  The `BaseUiApplication` extends the `BaseApplication` with the Twig and Asset
  serivce-providers registered. It also uses the `CacheTrait` to use an
  application-wide cache path, which may be set in the `app.cache` path.


## Providers

* `ThemeServiceProvider`

  The `ThemeServiceProvider` may be used to dynamically determine the paths for
  Twig views and assets.


## License

This project is licensed under the [MIT license](LICENSE).
