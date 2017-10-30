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
use SilexBase\BaseUiApplication;

/**
 * Test the `BaseApplication` with Silex's Web Profiler enabled.
 */
class BaseUiApplicationProfilerTest extends WebTestCase
{
    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        $app = new BaseUiApplication([
            'debug' => true,
            'app.cache' => __DIR__.'/../cache',
        ]);
        $app->enableProfiler();
        $app->get('/', function () use ($app) {
            return '<body>ok</body>';
        });

        return $app;
    }

    /**
     * Check if the Web Profiler is activated for requrests.
     */
    public function testWebProfilerIsActive()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');
        $response = $client->getResponse();

        /* Check the response for valid Web Profiler meta-data. */
        $this->assertTrue($response->isOk());
        $this->assertTrue($response->headers->has('X-Debug-Token'));
        $this->assertTrue($response->headers->has('X-Debug-Token-Link'));

        /* Check the URL generated for the profiler. */
        $link = $response->headers->get('X-Debug-Token-Link');
        $crawler = $client->request('GET', $link);
        $this->assertTrue($client->getResponse()->isOk());
    }

    /**
     * Check if the Web Profiler Dashboard is accessible.
     */
    public function testWebProfilerDashboard()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $client->request('GET', '/_profiler/');
        $this->assertTrue($client->getResponse()->isOk());
    }
}
