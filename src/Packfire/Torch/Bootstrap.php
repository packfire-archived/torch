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
 * Bootstrapper for ensuring that composer is installed
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @package Packfire\Torch
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class Bootstrap
{

    private static function acquire($file)
    {
        if (is_file($file)) {
            return include($file);
        }
    }

    public static function run()
    {
        if (!($loader = self::acquire(__DIR__ . '/../../../vendor/autoload.php'))) {
            echo 'You must set up project\'s dependencies first by running the following commands:' . PHP_EOL;
            echo "    curl -s https://getcomposer.org/installer | php\n";
            echo "    php composer.phar install\n";
            exit(1);
        }
        return $loader;
    }
}

return Bootstrap::run();
