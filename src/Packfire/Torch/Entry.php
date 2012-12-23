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
 * An asset entry 
 *
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\Torch
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class Entry {
    
    /**
     * The source entry
     * @var string
     * @since 1.0.0
     */
    public $source;
    
    /**
     * The target file entry
     * @var string
     * @since 1.0.0
     */
    public $file;
    
    /**
     * The version number of the entry
     * @var string
     * @since 1.0.0
     */
    public $version;
    
    /**
     * Create a new Entry object
     * @param array $entry The entry array
     * @since 1.0.0
     */
    public function __construct($entry){
        foreach($entry as $key => $value){
            $this->$key = $value;
        }
    }
    
}