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

use Buzz\Browser;
use Packfire\Options\OptionSet;
use Buzz\Client\Curl;

/**
 * Packfire Torch application core.
 * All magic starts here.
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @package Packfire\Torch
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class Torch
{
    
    const FILENAME = 'torch.json';
    
    /**
     * The option set processor
     * @var \Packfire\Option\OptionSet
     * @since 1.0.0
     */
    private $options;
    
    /**
     * The command to execute (first argument)
     * @var string
     * @since 1.0.0
     */
    private $command;
    
    /**
     * Create a new Torch object
     * @param array $args The array of arguments to pass into the application
     * @since 1.0.0
     */
    public function __construct($args)
    {
        array_shift($args);
        $this->options = new OptionSet();
        $this->options->addIndex(0, array($this, 'setCommand'));
        $this->options->parse($args);
    }
    
    /**
     * Run the application
     * @since 1.0.0
     */
    public function run()
    {
        echo "Packfire Torch\n";
        
        switch (strtolower($this->command)) {
            case 'install':
                if (is_file(self::FILENAME)) {
                    echo "Loading torch web assets information\n";
                    $meta = json_decode(file_get_contents(self::FILENAME), true);
                    $assets = $meta['assets'];
                    if ($assets && count($assets) > 0) {
                        $locker = new Locker('torch.lock');
                        $driver = new Curl();
                        $driver->setOption(CURLOPT_SSL_VERIFYPEER, false);
                        $installer = new Installer($locker, new Browser($driver));
                        foreach ($assets as $data) {
                            $entry = new Entry($data);
                            $installer->install($entry);
                        }
                        $locker->save();
                    } else {
                        echo "Nothing to install.";
                    }
                } else {
                    echo "Error\ntorch.json file not found.";
                }
                echo "\n";
                echo "Complete\n";
                break;
            default:
                echo "Version {{version}}\n";
                break;
        }
    }
    
    /**
     * Set the command
     * @param string $command The command
     * @since 1.0.0
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }
}
