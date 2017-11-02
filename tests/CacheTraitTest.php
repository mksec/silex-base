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

use Silex\Application;
use Silex\WebTestCase;
use SilexBase\Application\CacheTrait;

/**
 * Silex Application using the `CacheTrait`.
 */
class CacheAwareApplication extends Application
{
    use CacheTrait;
}

/**
 * Test the functions of the `CacheTrait`.
 *
 *
 * @see \SilexBase\Application\CacheTrait
 */
class CacheTraitTest extends WebTestCase
{
    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        return new CacheAwareApplication();
    }

    /**
     * Check if `cachePath` returns `null`, if no cache is set.
     */
    public function testNoCache()
    {
        $this->assertNull($this->app->cachePath('xyz'));
    }

    /**
     * Check if `cachePath` returns `null`, if the cache is set to `null`.
     */
    public function testNoCacheIfNull()
    {
        $this->app['app.cache'] = null;
        $this->assertNull($this->app->cachePath('xyz'));
    }

    /**
     * Check if `cachePath` returns `null`, if the cache is set to an empty
     * string.
     */
    public function testNoCacheIfEmpty()
    {
        $this->app['app.cache'] = '';
        $this->assertNull($this->app->cachePath('xyz'));
    }

    /**
     * Check if `cachePath` returns a valid cache path for a given identifier.
     */
    public function testCachePath()
    {
        $path = $this->app['app.cache'] = 'somedir';
        $identifier = 'xyz';

        $this->assertEquals(
            $this->app->cachePath($identifier),
            $path.'/'.$identifier
        );
    }
}
