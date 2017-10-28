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
use SilexBase\BaseApplication;

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
class BaseApplicationTest extends TestCase
{
    /**
     * Check that provided values will be passed to the base Silex Applicaton.
     */
    public function testPassedValues()
    {
        $app = new BaseApplication(['foo' => 'bar']);
        $this->assertEquals($app['foo'], 'bar');
    }
}
