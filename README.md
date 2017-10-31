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


## Installation

Simply add `mksec/silex-base` as a dependency for your project with composer:
```
composer require mksec/silex-base
```


## Usage

Using the basic application classes is pretty easy: Just use one of the classes
below instead of `\Silex\Application` and you are done. For specialized
applications you may want to implement a class inheriting from one of the base
classes.


#### Classes

* `BaseApplication`

  The `BaseApplication` class is a simple Silex Application with the Error- and
  ExceptionHandler registered. It may be used for simple applications without an
  UI (e.g. APIs).

* `BaseUiApplication`

  The `BaseUiApplication` extends the `BaseApplication` with the Twig and Asset
  serivce-providers registered. It also uses the `CacheTrait` to use an
  application-wide cache path, which may be set in the `app.cache` path.

  To use this class, you'll need to add additional dependencies:
  ```
  composer require symfony/asset symfony/twig-bridge twig/twig
  ```
  The [Web Profiler](https://github.com/silexphp/Silex-WebProfiler) may be
  activated with the `enableProfiler()` method **after** the other providers
  have been registered. You'll also need to add additional development
  dependencies:
  ```
  composer require --dev 'silex/web-profiler:^2.0'
  ```
  *Additional dependencies may be required depending on other providers you're
  using. See the [README](https://github.com/silexphp/Silex-WebProfiler) for the
  Silex Web Profiler for further information.*

There could be a lot more of sub-classes for any kind of Applications. However,
this might be inefficient, as there're too many combinations of services for
higher classes:

* There're situtions to use the `MonologServiceProvider` in any kind of
  application, so there's no sense to add extra classes just do get monolog in
  all of them. It is easier to just register the `MonologServiceProvider` if
  needed.
* The same goes for the security components (`SecurityServiceProvider`,
  `RememberMeServiceProvider`, `SessionServiceProvider`): Imagine an application
  with stateless authentication - there's no sense to use the latter two here.
* Interactive applications might use the `CsrfServiceProvider` and
  `FormServiceProvider`. However, their dependencies highly depend on the final
  application, i.e. if the default form templates will be used, the
  `TranslationServiceProvider` is required, but for custom templates it might be
  superfluous.

#### Providers

* `ThemeServiceProvider`

  The `ThemeServiceProvider` may be used to dynamically determine the paths for
  Twig views and assets. Although it is meant to be used with the
  `BaseUiApplication`, it is compatible with a plain Silex Application.


## Contribute

Everyone is welcome to contribute. Just fork this repository, make your changes
*in an own branch* and send a pull-request for your changes. Please send only
one change per pull-request.

You found a bug? Please
[file an issue](https://github.com/mksec/silex-base/issues/new) and include all
information to reproduce the bug.


## License

This project is licensed under the [MIT license](LICENSE).
