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

use Silex\Application\TwigTrait;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use SilexBase\Application\CacheTrait;

/**
 * Basic UI application.
 *
 * This class provides a basic Silex application for applications with UI. It is
 * based on top of the `BaseApplication` class and has the `CacheTrait`,
 * `AssetServiceProvider` and `TwigServiceProvider` loaded by default.
 *
 * Set the `app.cache` key for the application to use a filesystem cache for
 * Twig. It will be placed in the cache's `twig/` subdirectory.
 */
class BaseUiApplication extends BaseApplication
{
    use CacheTrait;
    use TwigTrait;

    /**
     * Register the TwigServiceProvider.
     *
     * To use the application-wide cache for Twig, the `cache` key in
     * `twig.options` needs to be set *before* Twig gets initialized. However,
     * the `twig.options` key can't be replaced with a closure. This method will
     * register the TwigServiceProvider and replace the factory with a
     * customized one to dynamically resolve the cache-path just before Twig
     * gets initialized.
     */
    private function registerTwig()
    {
        $this->register(new TwigServiceProvider());

        /* To enable dynamic configuration of Twig, the factory for Twig will be
         * extended. However, as some keys need to be defined BEFORE Twig's
         * constructor gets called, the extend() method of Pimple can't be used
         * and the factory will be replaced manually. */
        $factory = $this->raw('twig');
        $this['twig'] = function (BaseUiApplication $app) use ($factory) {
            /* The following default configuration will be used for Twig,
             * extending Silex's default configuration with a cache path
             * depending on the application-wide cache. */
            $app['twig.options'] = array_replace(
                [
                    'cache' => $app->cachePath('twig') ?? false,
                ],
                $app['twig.options']
            );

            /* Call the real factory with the original application but a
             * modified configuration. */
            return $factory($app);
        };
    }

    /**
     * Enable the Silex Web Profiler for this application.
     *
     * NOTICE: This method MUST NOT be called before all providers have been
     *         registered.
     *
     * NOTICE: The Web Profiler is only active in develop mode.
     *
     *
     * @return self
     */
    public function enableProfiler()
    {
        /* Register the HttpFragmentServiceProvider, if it isn't already
         * registered. */
        if (!isset($this['fragment.handler'])) {
            $this->register(new HttpFragmentServiceProvider());
        }

        /* Register the ServiceControllerServiceProvider, if it isn't already
         * registered. As this provider doesn't provide any services, but just
         * extends the resolver service, the providers array needs to be checked
         * instead of checking the services. */
        if (empty(array_filter($this->providers, function ($provider) {
            return $provider instanceof ServiceControllerServiceProvider;
        }))) {
            $this->register(new ServiceControllerServiceProvider());
        }

        /* Register the Web Profiler. Its cache will be stored in the
         * application-wide cache (in the 'profiler' subdirectory). */
        $this->register(new WebProfilerServiceProvider(), [
            'profiler.cache_dir' => function () {
                return $this->cachePath('profiler');
            },
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __construct(array $values = [])
    {
        /* Set default values for the application. One may override these values
         * by setting them in $values for early use (e.g. when the providers
         * will be registered) or later by accessing the created object via
         * array keys (as known for usual Silex applications). */
        $this['app.cache'] = null;

        /* Initialize the BaseApplication class. Predefined values for the
         * applications will be evaluated and override the default values set
         * above. */
        parent::__construct($values);

        /* Register essential providers required for a basic UI application.
         *
         * NOTICE: Instead of configuring the providers here, specialized
         *         methods will be used to get better structured code. */
        $this->register(new AssetServiceProvider());
        $this->registerTwig();
    }
}
