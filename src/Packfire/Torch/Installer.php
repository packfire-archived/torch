<?php

/**
 * This file is part of
 * Packfire Torch
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) 2012, Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Torch;

/**
 * Installer that helps to install web assets
 *
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\Torch
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class Installer
{
    /**
     * The browser used to download the web assets
     * @var \Buzz\Browser
     * @since 1.0.0
     */
    private $browser;

    /**
     * Create a new Installer object
     * @param \Buzz\Browser $browser Set the browser to download web assets
     * @since 1.0.0
     */
    public function __construct(\Buzz\Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * Install a web asset
     * @param array $data The data from the require section
     * @since 1.0.0
     */
    public function install($data)
    {
        $source = $data['source'];
        $target = $data['target'];
        $response = $this->browser->get($source);

        if (!is_dir($target)) {
            mkdir($target, 0777, true);
        }

        file_put_contents($target . DIRECTORY_SEPARATOR . basename($source), $response->getContent());
    }
}