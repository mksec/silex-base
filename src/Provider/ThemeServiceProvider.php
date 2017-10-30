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

namespace SilexBase\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Provider to manage the selected theme.
 *
 * This provider gives applications the ability to dynamically switch the theme
 * to be used by setting the `theme` key. Themes will be searched inside the
 * `theme.path` path.
 *
 * A theme may provide a file named `assets.php` in its root to provide an array
 * of named packages used by the theme and default values (e.g. the CDN
 * providing the asset). Users may override the packages by using `extend()` on
 * the `assets.named_packages` key to change the asset's path (e.g. using a
 * local copy instead of a CDN) or add additional packages.
 */
class ThemeServiceProvider implements ServiceProviderInterface
{
    /**
     * @param string $subdirectory optional subdirectory to be used
     *
     * @return string the path for the selected theme
     */
    private static function get_path(Container $app, string $path = ''): string
    {
        $base = rtrim($app['theme.path'], '/');
        $path = rtrim($path, '/');

        return $base.'/'.$app['theme'].($path ? '/'.$path : null);
    }

    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['theme'] = 'default';
        $app['theme.path'] = 'themes';

        /* Overload the 'twig.path' key with a closure to get the path for Twig
         * views on-demand depending on the selected theme. Views will reside in
         * the theme's '/views' subdirectory. */
        $app['twig.path'] = function (Container $app): string {
            return self::get_path($app, 'views');
        };

        /* Overload the 'assets.base_path' with a closure to get the theme's
         * root path. The contents of the 'assets.php' file in the theme's root
         * will be used as 'assets.named_packages' if available. */
        $app['assets.base_path'] = function (Container $app): string {
            return self::get_path($app);
        };
        $app['assets.named_packages'] = function (Container $app): array {
            $file = self::get_path($app).'/assets.php';

            return file_exists($file) ? require $file : [];
        };
    }
}
