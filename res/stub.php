#!/usr/bin/env php
<?php

/**
 * Packfire Torch
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * All rights reserved.
 */

Phar::mapPhar('torch.phar');
require 'phar://torch.phar/bin/torch';

__HALT_COMPILER();