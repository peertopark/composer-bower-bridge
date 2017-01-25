<?php

/*
 * This file is part of the Composer NPM bridge package.
 *
 * Copyright © 2016 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Peertopark\Composer\BowerBridge;

use Composer\IO\NullIO;
use PHPUnit_Framework_TestCase;

class BowerBridgeFactoryTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        $this->factory = BowerBridgeFactory::create();
        $this->io = new NullIO();
    }

    public function testCreateBridge() {
        $expected = new BowerBridge($this->io, new BowerVendorFinder(), BowerClient::create());

        $this->assertEquals($expected, $this->factory->createBridge($this->io));
    }

}
