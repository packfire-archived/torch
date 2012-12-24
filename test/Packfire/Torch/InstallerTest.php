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

use org\bovigo\vfs\vfsStream;

/**
 * Unit testing for \Packfire\Torch\Installer class
 *
 * @author Don Chan <http://github.com/donkun>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\Torch
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class InstallerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setUp('tmp');
    }

    private function getMockResponse()
    {
        $response = $this->getMock('\Buzz\Message\Response');
        $response->expects($this->once())
                 ->method('getContent')
                 ->will($this->returnValue('content'));

        return $response;
    }

    private function getMockBrowser()
    {
        $browser = $this->getMock('\Buzz\Browser');
        $browser->expects($this->once())
                ->method('get')
                ->with('source')
                ->will($this->returnValue($this->getMockResponse()));

        return $browser;
    }
    
    private function getMockLocker($entry){
        $locker = $this->getMock('\Packfire\Torch\Locker', array('check', 'lock'), array('torch.lock'));
        $locker->expects($this->once())
                ->method('check')
                ->with($entry)
                ->will($this->returnValue(true));
        $locker->expects($this->once())
                ->method('lock')
                ->with($entry);


        return $locker;
    }

    public function testInstall()
    {
        $entry = new Entry(array(
            'source' => 'source',
            'file' => vfsStream::url('tmp' . DIRECTORY_SEPARATOR . 'target' . DIRECTORY_SEPARATOR . 'source'),
            'version' => "1.8.1"
        ));
        $installer = new Installer($this->getMockLocker($entry), $this->getMockBrowser());
        ob_start();
        $installer->install($entry);
        $output = ob_get_clean();
        $this->assertEquals("\n  - Installing source (1.8.1)\n    Downloading...\n", $output);
        $this->assertEquals(file_get_contents(vfsStream::url('tmp' . DIRECTORY_SEPARATOR . 'target' . DIRECTORY_SEPARATOR . 'source')), 'content');
    }
}