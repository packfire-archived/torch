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

/**
 * Helps to provide compilation into a PHAR binary
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @package Packfire\Torch
 * @since 1.0.0
 * @link https://github.com/packfire/torch
 */

class Compiler extends \Packfire\Concrete\Compiler {
    
    protected function compile(){
        $this->processor = new \Packfire\Concrete\Processor\StripWhiteSpace();
        $this->addFile(new \SplFileInfo(__DIR__ . '/../../../license'));
        $this->addFolder(__DIR__ . '/../../../bin/');
        $this->addFolder(__DIR__ . '/../../');
        $this->addFolder(__DIR__ . '/../../../vendor/kriswallsmith/buzz');
        $this->addFolder(__DIR__ . '/../../../vendor/packfire/options');
        $this->addFolder(__DIR__ . '/../../../vendor/composer');
        $this->addFile(new \SplFileInfo(__DIR__ . '/../../../vendor/autoload.php'));

    }
    
    protected function stub(){
        $stub = <<<'EOF'
#!/usr/bin/env php
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

Phar::mapPhar('torch.phar');

EOF;

        // add warning once the phar is older than 30 days
        if (preg_match('{^[a-f0-9]+$}', $this->version)) {
            $stub .= "echo \"Warning: this is not a stable build\\n\";\n";
        }

        return $stub . <<<'EOF'
require 'phar://torch.phar/bin/torch';

__HALT_COMPILER();
EOF;
    }
    
}
