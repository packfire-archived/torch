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
 * Unit testing for \Packfire\Torch\Locker class
 *
 * @author Don Chan <http://github.com/donkun>
 * @copyright 2012 Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\Torch
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class LockerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setUp('tmp');
    }

    public function testCheckForNoPackages()
    {
        $lockPath = vfsStream::url('tmp/torch.lock');
        $locker = new Locker($lockPath);
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');

        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $this->assertTrue($locker->check($entry));
    }

    public function testCheckEntryWithNonFile()
    {
        $lockPath = vfsStream::url('tmp/torch.lock');
        $locker = new Locker($lockPath);
        $target = vfsStream::url('tmp/dir');
        mkdir($target, 0777, true);

        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $this->assertTrue($locker->check($entry));
    }

    public function testCheckEntryNotMatchingAtLeastOnePackage()
    {
        $lockPath = vfsStream::url('tmp/torch.lock');
        file_put_contents(
            $lockPath,
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => 'irrelevant1',
                            'version' => 'irrelevant1' ,
                            'hash' => 'irrelevant1',
                        ),
                        array(
                            'file' => 'irrelevant2',
                            'version' => 'irrelevant2' ,
                            'hash' => 'irrelevant2',
                        )
                    )
                )
            )
        );
        $locker = new Locker($lockPath);

        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');
        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $this->assertTrue($locker->check($entry));
    }

    public function testCheckEntryMatchingPackageHashOnly()
    {
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');
        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $lockPath = vfsStream::url('tmp/torch.lock');
        file_put_contents(
            $lockPath,
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => $entry->file,
                            'version' => 'irrelevant' ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                    )
                )
            )
        );
        $locker = new Locker($lockPath);

        $this->assertTrue($locker->check($entry));
    }

    public function testCheckEntryMatchingPackageVersionOnly()
    {
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');
        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $lockPath = vfsStream::url('tmp/torch.lock');
        file_put_contents(
            $lockPath,
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => 'irrevelant',
                        ),
                    )
                )
            )
        );
        $locker = new Locker($lockPath);

        $this->assertTrue($locker->check($entry));
    }

    public function testCheckEntryMatchingPackageAllButDifferentFilename()
    {
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');
        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $lockPath = vfsStream::url('tmp/torch.lock');
        file_put_contents(
            $lockPath,
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => 'irrelevant',
                            'version' => $entry->version ,
                            'hash' => 'irrevelant',
                        ),
                    )
                )
            )
        );
        $locker = new Locker($lockPath);

        $this->assertTrue($locker->check($entry));
    }

    public function testCheckEntryMatchingPackageAll()
    {
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');
        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $lockPath = vfsStream::url('tmp/torch.lock');
        file_put_contents(
            $lockPath,
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                    )
                )
            )
        );
        $locker = new Locker($lockPath);

        $this->assertFalse($locker->check($entry));
    }

    public function testCheckEntryMatchingPackageAllCanHandleDuplicate()
    {
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');
        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $lockPath = vfsStream::url('tmp/torch.lock');
        file_put_contents(
            $lockPath,
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                    )
                )
            )
        );
        $locker = new Locker($lockPath);

        $this->assertFalse($locker->check($entry));
    }

    public function testLockEntryWithEmptyLock()
    {
        $lockPath = vfsStream::url('tmp/torch.lock');
        $locker = new Locker($lockPath);
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');

        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $locker->lock($entry);
        $locker->save();
        $this->assertEquals(
            file_get_contents($lockPath),
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        )
                    )
                )
            )
        );
    }

    public function testLockAddMultipleEntriesWithEmptyLock()
    {
        $lockPath = vfsStream::url('tmp/torch.lock');
        $locker = new Locker($lockPath);

        $target1 = vfsStream::url('tmp/file');
        file_put_contents($target1, 'contents 1 for hashing');

        $entry1 = new Entry(
            array(
                'source' => 'source1',
                'file' => $target1,
                'version' => "1.8.1",
            )
        );

        $locker->lock($entry1);

        $target2 = vfsStream::url('tmp/file2');
        file_put_contents($target2, 'contents 2 for hashing');

        $entry2 = new Entry(
            array(
                'source' => 'source2',
                'file' => $target2,
                'version' => "1.8.2",
            )
        );

        $locker->lock($entry2);
        $locker->save();
        $this->assertEquals(
            file_get_contents($lockPath),
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => $entry1->file,
                            'version' => $entry1->version ,
                            'hash' => hash_file('sha256', $entry1->file),
                        ),
                        array(
                            'file' => $entry2->file,
                            'version' => $entry2->version ,
                            'hash' => hash_file('sha256', $entry2->file),
                        ),
                    )
                )
            )
        );
    }

    public function testLockEntryWithEntryNotExisted()
    {
        $lockPath = vfsStream::url('tmp/torch.lock');

        file_put_contents(
            $lockPath,
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => 'irrelevant1',
                            'version' => 'irrelevant1' ,
                            'hash' => 'irrelevant1',
                        ),
                        array(
                            'file' => 'irrelevant2',
                            'version' => 'irrelevant2' ,
                            'hash' => 'irrelevant2',
                        ),
                    )
                )
            )
        );

        $locker = new Locker($lockPath);
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');

        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $locker->lock($entry);
        $locker->save();
        $this->assertEquals(
            file_get_contents($lockPath),
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => 'irrelevant1',
                            'version' => 'irrelevant1' ,
                            'hash' => 'irrelevant1',
                        ),
                        array(
                            'file' => 'irrelevant2',
                            'version' => 'irrelevant2' ,
                            'hash' => 'irrelevant2',
                        ),
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                    )
                )
            )
        );
    }

    public function testLockEntryWithEntryExisted()
    {
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');

        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $lockPath = vfsStream::url('tmp/torch.lock');

        file_put_contents(
            $lockPath,
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => 'irrelevant1',
                            'version' => 'irrelevant1' ,
                            'hash' => 'irrelevant1',
                        ),
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                    )
                )
            )
        );

        $locker = new Locker($lockPath);
        $locker->lock($entry);
        $locker->save();
        $this->assertEquals(
            file_get_contents($lockPath),
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => 'irrelevant1',
                            'version' => 'irrelevant1' ,
                            'hash' => 'irrelevant1',
                        ),
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                    )
                )
            )
        );
    }

    public function testLockEntryWithEntryExistedInTheCorrectOrder()
    {
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');

        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $lockPath = vfsStream::url('tmp/torch.lock');

        file_put_contents(
            $lockPath,
            json_encode(
                array(
                'packages' => array(
                    array(
                        'file' => 'irrelevant1',
                        'version' => 'irrelevant1' ,
                        'hash' => 'irrelevant1',
                    ),
                    array(
                        'file' => $entry->file,
                        'version' => $entry->version ,
                        'hash' => hash_file('sha256', $entry->file),
                    ),
                    array(
                        'file' => 'irrelevant2',
                        'version' => 'irrelevant2' ,
                        'hash' => 'irrelevant2',
                    ),
                )
            )
            )
        );

        $locker = new Locker($lockPath);
        $locker->lock($entry);
        $locker->save();
        $this->assertEquals(
            file_get_contents($lockPath),
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => 'irrelevant1',
                            'version' => 'irrelevant1' ,
                            'hash' => 'irrelevant1',
                        ),
                        array(
                            'file' => 'irrelevant2',
                            'version' => 'irrelevant2' ,
                            'hash' => 'irrelevant2',
                        ),
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                    )
                )
            )
        );
    }

    public function testLockEntryWithEntryExistingDuplicate()
    {
        $target = vfsStream::url('tmp/file');
        file_put_contents($target, 'contents for hashing');

        $entry = new Entry(
            array(
                'source' => 'source',
                'file' => $target,
                'version' => "1.8.1",
            )
        );

        $lockPath = vfsStream::url('tmp/torch.lock');

        file_put_contents(
            $lockPath,
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => 'irrelevant1',
                            'version' => 'irrelevant1' ,
                            'hash' => 'irrelevant1',
                        ),
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                        array(
                            'file' => 'irrelevant2',
                            'version' => 'irrelevant2' ,
                            'hash' => 'irrelevant2',
                        ),
                    )
                )
            )
        );

        $locker = new Locker($lockPath);
        $locker->lock($entry);
        $locker->save();
        $this->assertEquals(
            file_get_contents($lockPath),
            json_encode(
                array(
                    'packages' => array(
                        array(
                            'file' => 'irrelevant1',
                            'version' => 'irrelevant1' ,
                            'hash' => 'irrelevant1',
                        ),
                        array(
                            'file' => 'irrelevant2',
                            'version' => 'irrelevant2' ,
                            'hash' => 'irrelevant2',
                        ),
                        array(
                            'file' => $entry->file,
                            'version' => $entry->version ,
                            'hash' => hash_file('sha256', $entry->file),
                        ),
                    )
                )
            )
        );
    }
}
