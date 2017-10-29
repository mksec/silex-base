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

use PHPUnit\Framework\TestCase;
use SilexBase\BaseUiApplication;
use Twig_Environment;

/**
 * Test the `BaseApplication`.
 *
 * The testcases defined in this class will check the `BaseApplication`.
 * However, the functionality of Symfony's Error- and ExceptionHandler can't be
 * tested by phpunit.
 *
 *
 * @see \SilexBase\BaseUiApplication
 */
class BaseUiApplicationTest extends TestCase
{
    /**
     * Instance of a Silex application.
     */
    protected $app;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->app = new BaseUiApplication();
    }

    /**
     * Check that provided values will be passed to the base Silex Applicaton.
     */
    public function testPassedValues()
    {
        /* Use a custom $app instance, as the global one of this test case
         * doesn't accept values in the constructor. */
        $app = new BaseUiApplication(['foo' => 'bar']);
        $this->assertEquals($app['foo'], 'bar');
    }

    /**
     * Check if Twig get's loaded into the application and has the required
     * default values.
     */
    public function testTwigProvider()
    {
        $this->assertInstanceOf(Twig_Environment::class, $this->app['twig']);
        $this->assertEquals($this->app['twig.options']['cache'], false);
    }

    /**
     * Check if Twig's cache-path gets set properly if `app.cache` is set.
     */
    public function testTwigProviderCachePath()
    {
        $this->app['app.cache'] = __DIR__;
        $this->app['twig'];
        $this->assertEquals(
            $this->app['twig.options']['cache'],
            __DIR__.'/twig'
        );
    }

    /*
     * Check if Twig get's loaded into the application.
     */
    public function testAssetProvider()
    {
        $this->assertArrayHasKey('assets.version', $this->app);
    }
}
