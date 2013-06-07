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

namespace Packfire\Torch\Repository;

/**
 * Repository interface
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @package Packfire\Torch\Repository
 * @since 1.1.0
 * @link https://github.com/packfire/torch
 */
interface IRepository
{
    public function find($name, $version = null);
}
