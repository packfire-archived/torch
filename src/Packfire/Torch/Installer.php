<?php

/**
 * This file is part of
 * Packfire Torch
 * By Sam-Mauris Yong
 *
 * Released open source under BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Torch;

/**
 * Installer that helps to install web assets
 *
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
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
     * The lock file manager
     * @var \Packfire\Torch\Locker
     * @since 1.0.0
     */
    private $locker;

    /**
     * Create a new Installer object
     * @param \Packfire\Torch\Locker $locker Set the lock file manager instance
     * @param \Buzz\Browser $browser Set the browser to download web assets
     * @since 1.0.0
     */
    public function __construct(\Packfire\Torch\Locker $locker, \Buzz\Browser $browser){
        $this->locker = $locker;
        $this->browser = $browser;
    }

    /**
     * Install a web asset
     * @param \Packfire\Torch\Entry $data The data from the require section
     * @since 1.0.0
     */
    public function install($entry){
        $target = $entry->file;
        $targetName = basename($target);
        if($this->locker->check($entry)){
            $version = $entry->version;
            echo "\n  - Installing $targetName ($version)\n";
            $source = $entry->source;
            echo "    Downloading...\n";
            $response = $this->browser->get($source, array('User-Agent' => 'Packfire Torch/' . Torch::VERSION));

            $targetDir = dirname($target);
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if(is_file($target)){
                unlink($target);
            }
            // todo check if $target is dir
            file_put_contents($target, $response->getContent());
            $this->locker->lock($entry);
        }
    }
}