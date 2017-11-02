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

namespace SilexBase\Tests;

use Silex\WebTestCase;
use SilexBase\BaseApplication;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

/**
 * Test the `BaseApplication`.
 *
 * The testcases defined in this class will check the `BaseApplication`.
 * However, the functionality of Symfony's Error- and ExceptionHandler can't be
 * tested by phpunit.
 *
 *
 * @see \SilexBase\BaseApplication
 */
class BaseApplicationTest extends WebTestCase
{
    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        return new BaseApplication();
    }

    /**
     * Check that provided values will be passed to the base Silex Applicaton.
     */
    public function testPassedValues()
    {
        /* As we need to pass arguments to the constructor, we can't use the
         * default application instance. */
        $app = new BaseApplication(['foo' => 'bar']);
        $this->assertEquals($app['foo'], 'bar');
    }

    /**
     * Check that the error handlers is available from outside.
     */
    public function testErrorHandler()
    {
        $this->assertArrayHasKey('core.error_handler', $this->app);
        $this->assertInstanceOf(
            ErrorHandler::class,
            $this->app['core.error_handler']
        );
    }

    /**
     * Check that the exception handlers is available from outside.
     */
    public function testExceptionHandler()
    {
        $this->assertArrayHasKey('core.exception_handler', $this->app);
        $this->assertInstanceOf(
            ExceptionHandler::class,
            $this->app['core.exception_handler']
        );
    }

    /**
     * Check the exception handler can be told to route events back to Silex.
     *
     * NOTICE: This test can't check, if events will be routed back, but it'll
     *         check no errors happening on calling the method.
     */
    public function testCatchAllExceptions()
    {
        $this->app->catchAllExceptions();
        $this->assertTrue(true);
    }
}
