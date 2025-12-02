<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019 - 2022, CodeIgniter Foundation
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
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package      CodeIgniter
 * @author       CodeIgniter Dev Team
 * @copyright    Copyright (c) 2019 - 2022, CodeIgniter Foundation
 * @license      https://opensource.org/licenses/MIT  MIT License
 * @link         https://codeigniter.com
 * @since        Version 4.0.0
 * @filesource
 */

// This is the path to your application's public folder.
$publicPath = __DIR__ . '/../public';

// This is the path to your front controller.
$indexFile = $publicPath . '/index.php';

// Determine the requested URI.
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Set the path to the front controller.
$_SERVER['SCRIPT_NAME'] = '/index.php';

// If the request is for a static file, serve it directly.
if (is_file($publicPath . $uri)) {
    return false; // Let the server handle the static file.
}

// If the request is for the front controller, let the server handle it.
if ($uri === '/index.php') {
    return false;
}

// Otherwise, route the request to the front controller.
require $indexFile;
