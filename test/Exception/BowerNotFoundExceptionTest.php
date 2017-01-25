<?php

/*
 * This file is part of the Composer NPM bridge package.
 *
 * Copyright © 2016 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Peertopark\Composer\BowerBridge\Exception;

use Exception;
use PHPUnit_Framework_TestCase;

class BowerNotFoundExceptionTest extends PHPUnit_Framework_TestCase {

    public function testException() {
        $cause = new Exception();
        $exception = new BowerNotFoundException($cause);

        $this->assertSame('The bower executable could not be found.', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($cause, $exception->getPrevious());
    }

}
