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

use Packfire\Torch\Util\ListSearch;

/**
 * Lock file generation
 *
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @package Packfire\Torch
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */
class Locker
{

    /**
     * Path to the lock file to generate
     * @var \SplFileInfo
     * @since 1.0.0
     */
    private $file;

    /**
     * The packages locked read from the lock file
     * @var array
     * @since 1.0.0
     */
    private $packages;

    /**
     * Create a new Locker object
     * @param string|\SplFileInfo $path Path to the lock file to generate
     * @since 1.0.0
     */
    public function __construct($path)
    {
        if ($path instanceof \SplFileInfo) {
            $this->file = $path;
        } else {
            $this->file = new \SplFileInfo($path);
        }
        if ($this->file->isFile()) {
            $data = json_decode(file_get_contents($this->file->getPathname()), true);
            $this->packages = $data['packages'];
        } else {
            $this->packages = array();
        }
    }

    /**
     * Check whether an entry requires update
     * @param \Packfire\Torch\Entry $entry The asset entry
     * @return boolean Returns true if require update, false otherwise.
     * @since 1.0.0
     */
    public function check(Entry $entry)
    {
        // check if target file exists
        // if does not exist then yes we must require update
        if (is_file($entry->file)) {
            // check for lock file
            if ($this->packages) {
                $ok = false;
                $results = ListSearch::search($this->packages, 'file', $entry->file);
                foreach ($results as $result) {
                    $ok = $entry->version == $result['version'] && hash_file('sha256', $entry->file) == $result['hash'];
                    if (!$ok) {
                        break;
                    }
                }
                if ($ok) {
                    return false;
                }
            }
        }
        return true;
    }
    /**
     * Perform an asset revision lock
     * @param \Packfire\Torch\Entry $entry The asset entry
     * @since 1.0.0
     */
    public function lock(Entry $entry)
    {
        $results = ListSearch::search($this->packages, 'file', $entry->file);
        foreach ($results as $result) {
            if (($key = array_search($result, $this->packages)) !== false) {
                unset($this->packages[$key]);
            }
        }
        $this->packages = array_values($this->packages);
        $this->packages[] = array(
            'file' => $entry->file,
            'version' => $entry->version,
            'hash' => hash_file('sha256', $entry->file)
        );
    }

    /**
     * Performs writing to the file when the class is destroyed.
     * @since 1.0.0
     */
    public function save()
    {
        file_put_contents($this->file->getPathname(), json_encode(array('packages' => $this->packages)));
    }
}
