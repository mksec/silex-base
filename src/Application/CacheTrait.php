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

namespace SilexBase\Application;

/**
 * Functions to get file system cache paths.
 *
 * Services like Twig or Doctrine use cache directories for storing cache files
 * to accelerate the application. To simplify the configuration of the
 * application, all providers configured for this package will use the common
 * cache path defined in the `app.cache` key of the container. This trait
 * provides functions to get a valid cache path for the individual providers.
 */
trait CacheTrait
{
    /**
     * @param string $identifier for which service the cache path is required
     *
     * @return string|null cache path for `$identifier` or `null`, if
     *                     `cache.path` is not defined
     */
    public function cachePath(string $identifier)
    {
        if (isset($this['app.cache'])) {
            return rtrim($this['app.cache'], '/').'/'.$identifier;
        }
    }
}
