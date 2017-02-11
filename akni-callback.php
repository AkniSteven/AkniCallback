<?php
/*
    Plugin Name: Akni Callback
    Plugin URI:
    Description: Create custom theme settings
    Version: 0.0.1
    Author: Stevenaknidev@gmail.com
    Author URI: https://ua.linkedin.com/in/steve-arshinikov-5a4184aa
*/
/*
Copyright (c) 2017 stevenaknidev@gmail.com https://www.linkedin.com/

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
use AkniCallback\Model\Constructor;

/**
 * Use composer
 */
if (file_exists($composerAutoload = __DIR__ . '/vendor/autoload.php')) {
    require_once $composerAutoload;
} else {
    _e('Install composer for current work');
    exit;
}
$pluginDir = __DIR__;

$constructor = Constructor::getInstance( $pluginDir );