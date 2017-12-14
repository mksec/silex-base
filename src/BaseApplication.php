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

use Exception;
use Silex\Application;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Basic application.
 *
 * This class provides a basic Silex application. It has the Error- and
 * ExceptionHandler already registered.
 */
class BaseApplication extends Application
{
    /**
     * Wheter this application runs in console mode or not.
     *
     *
     * @var bool
     */
    protected static $consoleMode = false;

    /**
     * Enable console mode for the application.
     *
     * If the application is used in console applications (e.g. the doctrine
     * tools, PHPUnit, ...), the Application needs to be aware of it. E.g. the
     * error- and exception handler must not be registered to not convert
     * exceptions to HTML pages.
     */
    public static function enableConsoleMode()
    {
        static::$consoleMode = true;
    }

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
        if (!self::$consoleMode) {
            $this['core.error_handler'] = ErrorHandler::register();
            $this['core.exception_handler'] =
                ExceptionHandler::register($values['debug'] ?? false);
        }

        /* Initialize the Silex Application class. Predefined values will be
         * passed to its constructor to setup the application. */
        parent::__construct($values);
    }

    /**
     * Tell the `ExceptionHandler` to route exceptions thrown outside the
     * contoller.
     *
     * By default, the exception handlers registered with `error()` will not be
     * called for fatal errors and exceptions thrown outside the running
     * controller. This method will tell the exception handler in
     * `core.exception_handler` to route exceptions back to the internal
     * exception handling stack.
     *
     * NOTICE: You should be sure about what you're doing if calling this
     *         method, as it has side effects. E.g. the request in the event may
     *         be empty, no custom status code may be set and additional
     *         services (like the Web Profiler) will **NOT** be triggered.
     *
     *
     * @return self
     */
    public function catchAllExceptions()
    {
        /* This snippet is based on an answer of noemi-salaun
         * (https://github.com/noemi-salaun) in GitHub issue #1016 of the Silex
         * repository (https://github.com/silexphp/Silex/issues/1016) */
        $this['core.exception_handler']->setHandler(function (Exception $e) {
            /* Create an ExceptionEvent with all the informations needed and
             * dispatch it.
             *
             * NOTICE: If the exception is thrown outside a running controller,
             *         the request will be empty. For API compatiblity a new
             *         Request will be created from the globals and passed in
             *         this case. */
            $event = new GetResponseForExceptionEvent(
                $this,
                $this['request_stack']->getCurrentRequest()
                    ?? Request::createFromGlobals(),
                HttpKernelInterface::MASTER_REQUEST,
                $e
            );
            $this['dispatcher']->dispatch(KernelEvents::EXCEPTION, $event);

            /* Get the response generated by the event and send it back to the
             * client. The status-code will be overridden to error 500. If no
             * error handler returned a proper response, these calls will fail
             * and the ExceptionHandler will print its default error page. */
            if ($response = $event->getResponse()) {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                         ->sendHeaders()
                         ->sendContent();
            }
        });

        return $this;
    }
}
