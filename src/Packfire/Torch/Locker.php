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
 * Lock file generation
 *
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\Torch
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class Locker {
    
    /**
     * Path to the lock file to generate 
     * @var \SplFileInfo 
     * @since 1.0.0
     */
    private $file;
    
    /**
     * Create a new Locker object
     * @param string|\SplFileInfo $path Path to the lock file to generate
     * @since 1.0.0
     */
    public function __construct($path){
        if($path instanceof \SplFileInfo){
            $this->file = $path;
        }else{
            $this->file = new \SplFileInfo($path);
        }
    }
    
    /**
     * Check whether an entry requiers update
     * @param array $entry The asset entry
     * @return boolean Returns true if require update, false otherwise.
     * @since 1.0.0
     */
    public function check($entry){
        
    }
    
    /**
     * Perform an asset revision lock
     * @param array $entry The asset entry
     * @since 1.0.0
     */
    public function lock($entry){
        
    }
    
}