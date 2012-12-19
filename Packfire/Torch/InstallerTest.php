<?php
namespace Packfire\Torch;

use org\bovigo\vfs\vfsStream;

class InstallerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setUp('tmp');
    }

    private function getMockResponse()
    {
        $response = $this->getMock('Buzz\Message\Response');
        $response->expects($this->once())
                 ->method('getContent')
                 ->will($this->returnValue('content'));

        return $response;
    }

    private function getMockBrowser()
    {
        $browser = $this->getMock('Buzz\Browser');
        $browser->expects($this->once())
                ->method('get')
                ->with('source')
                ->will($this->returnValue($this->getMockResponse()));

        return $browser;
    }

    public function testInstall()
    {
        $installer = new Installer($this->getMockBrowser());
        $installer->install(array('source' => 'source', 'target' => vfsStream::url('tmp')));
        $this->assertEquals(file_get_contents(vfsStream::url('tmp' . DIRECTORY_SEPARATOR . 'source')), 'content');
    }
}