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
use Silex\Application;
use SilexBase\Provider\ThemeServiceProvider;

/**
 * Test the `ThemeServiceProvider`.
 *
 *
 * @see \SilexBase\Provider\ThemeServiceProvider
 */
class ThemeServiceProviderTest extends TestCase
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
        $this->app = new Application();
        $this->app->register(new ThemeServiceProvider());
    }

    /**
     * Check the default path for Twig views.
     */
    public function testTwigPathDefault()
    {
        $this->assertEquals($this->app['twig.path'], 'themes/default/views');
    }

    /**
     * Check the path for Twig views.
     */
    public function testTwigPath()
    {
        $this->app['theme'] = 'foo';
        $this->app['theme.path'] = __DIR__;
        $this->assertEquals($this->app['twig.path'], __DIR__.'/foo/views');
    }

    /**
     * Check the default base path for assets.
     */
    public function testAssetBasePathDefault()
    {
        $this->assertEquals($this->app['assets.base_path'], 'themes/default');
    }

    /**
     * Check the base path for assets.
     */
    public function testAssetBasePath()
    {
        $this->app['theme'] = 'foo';
        $this->app['theme.path'] = __DIR__;
        $this->assertEquals($this->app['assets.base_path'], __DIR__.'/foo');
    }

    /**
     * Check the manifest file to be used if theme has no manifest file.
     */
    public function testAssetNoManifest()
    {
        $this->assertEmpty($this->app['assets.json_manifest_path']);
    }

    /**
     * Check the manifest file to be used if theme has a manifest file.
     */
    public function testAssetManifest()
    {
        $this->app['theme'] = 'testTheme';
        $this->app['theme.path'] = __DIR__;
        $this->assertEquals(
            __DIR__.'/testTheme/assets.manifest.json',
            $this->app['assets.json_manifest_path']
        );
    }

    /**
     * Check the named packages for assets (if no file is present).
     */
    public function testAssetNamedPackagesEmpty()
    {
        $this->app['theme'] = 'unknown';
        $this->app['theme.path'] = __DIR__;
        $this->assertEmpty($this->app['assets.named_packages']);
    }

    /**
     * Check the named packages for assets.
     */
    public function testAssetNamedPackages()
    {
        $this->app['theme'] = 'testTheme';
        $this->app['theme.path'] = __DIR__;
        $this->assertArrayHasKey('pkg1', $this->app['assets.named_packages']);
    }

    /**
     * Check the named packages for assets (if the packages are extended).
     */
    public function testAssetNamedPackagesExtended()
    {
        $this->app['theme'] = 'testTheme';
        $this->app['theme.path'] = __DIR__;

        $this->app->extend('assets.named_packages', function (array $packages) {
            $packages['pkg5'] = [];

            return $packages;
        });

        $this->assertArrayHasKey('pkg1', $this->app['assets.named_packages']);
        $this->assertArrayHasKey('pkg5', $this->app['assets.named_packages']);
    }
}
