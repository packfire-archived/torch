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

namespace Packfire\Torch\Util;

/**
 * List Searcher Utility
 *
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\Torch\Util
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class ListSearch {
    
    /**
     * The results of the search
     * @var array
     * @since 1.0.0
     */
    protected $results;
    
    /**
     * The current recursion level
     * @var integer
     * @since 1.0.0
     */
    protected $current = 0;

    /**
     * The maximum recursion level
     * @var integer
     * @since 1.0.0
     */
    protected $maxLevel;
    
    /**
     * The key of the array to search for
     * @var string|integer
     * @since 1.0.0
     */
    protected $key;
    
    /**
     * The value to match in the key
     * @var mixed
     * @since 1.0.0
     */
    protected $value;
    
    /**
     * Create a new ListSearch object
     * @param string|integer $key The key to check
     * @param mixed $value The value of the key to match
     * @param integer $level The maximum recursion level
     * @since 1.0.0
     */
    protected function __construct($key, $value, $level){
        $this->key = $key;
        $this->value = $value;
        $this->maxLevel = $level;
    }
    
    /**
     * Perform a search in an array
     * @param array $array An array to search recursively for a key value pair
     * @param string|integer $key The key to check
     * @param mixed $value The value of the key to match
     * @param integer $level (optional) The maximum recursion level. Defaults to 1
     * @return array Returns an array of the search result.
     * @since 1.0.0
     */
    public static function search($array, $key, $value, $level = 1){
        if(is_array($array)){
            $search = new self($key, $value, $level);
            $search->find($array);
            return $search->results;
        }
    }

    /**
     * Perform an internal recursive search function
     * @param array $array The array to search in
     * @since 1.0.0
     */
    protected function find($array){
        if ($array[$this->key] == $this->value){
            $this->results[] = $array;
        }
        
        if($this->current < $this->maxLevel){
            ++$this->current;
            foreach ($array as $subarray){
                if(is_array($subarray)){
                    $this->find($subarray);
                }
            }
            --$this->currentLevel;
        }
    }

}