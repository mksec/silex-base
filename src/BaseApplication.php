<?php

/* This file is part of silex-base.
 *
 * Copyright (C)
 *  2017 Alexander Haase <ahaase@mksec.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace SilexBase;

use Silex\Application;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

/**
 * Basic application.
 *
 * This class provides a basic Silex application. It has the Error- and
 * ExceptionHandler already registered.
 */
class BaseApplication extends Application
{
    /**
     * {@inheritdoc}
     *
     * NOTICE: By default, the exception handler will *NOT* print any debug
     *         information (e.g. stack traces). To enable the debug information,
     *         call the constructor with the `debug` key in `$values` set to
     *         `true`.
     */
    public function __construct(array $values = [])
    {
        /* Silex catches exceptions that are thrown from within a request /
         * response cycle. However, it does not catch PHP errors and notices.
         * The Symfony/Debug package has an ErrorHandler class that solves this
         * problem. To handle fatal errors, the ExceptionHandler will be used in
         * addition.
         *
         * For more information see
         * http://silex.sensiolabs.org/doc/cookbook/error_handler.html
         *
         * NOTICE: By default, the exception handler will be used without debug
         *         output. Developers need to create a new Application object
         *         'debug' set to true in $values to enable this feature. */
        $this['core.error_handler'] = ErrorHandler::register();
        $this['core.exception_handler'] =
            ExceptionHandler::register($values['debug'] ?? false);

        /* Initialize the Silex Application class. Predefined values will be
         * passed to its constructor to setup the application. */
        parent::__construct($values);
    }
}
