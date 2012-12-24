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
 * Unit testing for \Packfire\Torch\Util\ListSearch class
 *
 * @author Don Chan <http://github.com/donkun>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\Torch\Util
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class ListSearchTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Packfire\Torch\Util\ListSearch::search
     */
    public function testSearch()
    {
        $haystack = array(
            'alpha' => true,
            'bravo' => 4,
            'charlie' => array('5')
        );
        $results = ListSearch::search($haystack, 'charlie', array('5'));
        $this->assertEquals($haystack, $results[0]);
    }

    /**
     * @covers Packfire\Torch\Util\ListSearch::search
     */
    public function testSearch2()
    {
        $haystack = array(
            'alpha' => true,
            'bravo' => 4,
            'charlie' => array(
                'alpha' => 5,
                'bravo' => 6
            )
        );
        $results = ListSearch::search($haystack, 'bravo', 6, 0);
        $this->assertCount(0, $results);
    }

    /**
     * @covers Packfire\Torch\Util\ListSearch::search
     */
    public function testSearch3()
    {
        $haystack = array(
            'alpha' => true,
            'bravo' => 4,
            'charlie' => array(
                'alpha' => 5,
                'bravo' => 6
            )
        );
        $results = ListSearch::search($haystack, 'bravo', 6);
        $this->assertEquals($haystack['charlie'], $results[0]);
    }

    /**
     * @covers Packfire\Torch\Util\ListSearch::search
     */
    public function testSearch4()
    {
        $haystack = array(
            'alpha' => true,
            'bravo' => 4
        );
        $results = ListSearch::search($haystack, 'bravo', '4');
        $this->assertCount(0, $results);
    }
    
}
