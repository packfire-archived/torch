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

use Buzz\Browser;
use Packfire\Options\OptionSet;

/**
 * Packfire Torch application core.
 * All magic starts here.
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\Torch
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class Torch {
    
    const FILENAME = 'torch.json';
    
    private $options;
    
    private $command;
    
    public function __construct($args){
        array_shift($args);
        $this->options = new OptionSet();
        $this->options->addIndex(0, array($this, 'setCommand'));
        $this->options->parse($args);
    }
    
    public function run(){
        echo "Packfire Torch\n\n";
        
        switch(strtolower($this->command)){
            case 'install':
                echo "Installing... ";
                if(is_file(self::FILENAME)){
                    $meta = json_decode(file_get_contents(self::FILENAME), true);
                    $installer = new Installer(new Browser());
                    foreach($meta['require'] as $entry){
                        $installer->install($entry);
                    }
                }else{
                    echo "Error\ntorch.json file not found.";
                }
                echo "\n";
                echo "Complete\n";
                break;
        }
    }
    
    public function setCommand($command){
        $this->command = $command;
    }
    
}