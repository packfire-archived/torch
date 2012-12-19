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
 * @link https://github.com/packfire/pdc/
 */
class Torch {
    
    private $options;
    
    private $command;
    
    public function __construct($args){
        array_shift($args);
        $this->options = new OptionSet();
        $this->options->addIndex(0, array($this, 'setCommand'));
        $this->options->parse($args);
    }
    
    public function run(){
        switch(strtolower($this->command)){
            case 'install':
                break;
        }
    }
    
    public function setCommand($command){
        $this->command = $command;
    }
    
}