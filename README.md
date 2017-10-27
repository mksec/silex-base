# Silex Base-Application

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


## License

This project is licensed under the [MIT license](LICENSE).
