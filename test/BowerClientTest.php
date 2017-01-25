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

use Composer\Util\ProcessExecutor;
use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Process\ExecutableFinder;

class BowerClientTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        $this->processExecutor = Phony::mock('Composer\Util\ProcessExecutor');
        $this->executableFinder = Phony::mock('Symfony\Component\Process\ExecutableFinder');
        $this->getcwd = Phony::stub();
        $this->chdir = Phony::stub();
        $this->client = new BowerClient($this->processExecutor->mock(), $this->executableFinder->mock(), $this->getcwd, $this->chdir);

        $this->processExecutor->execute('*')->returns(0);
        $this->executableFinder->find('bower', 'node_modules/.bin/bower')->returns('/path/to/bower');
        $this->getcwd->returns('/path/to/cwd');
    }

    public function testInstall() {
        $this->assertNull($this->client->install('/path/to/project'));
        $this->assertNull($this->client->install('/path/to/project'));
        Phony::inOrder(
                $this->executableFinder->find->calledWith('bower', 'node_modules/.bin/bower'), $this->chdir->calledWith('/path/to/project'), $this->processExecutor->execute->calledWith("'/path/to/bower' 'install'"), $this->chdir->calledWith('/path/to/cwd'), $this->chdir->calledWith('/path/to/project'), $this->processExecutor->execute->calledWith("'/path/to/bower' 'install'"), $this->chdir->calledWith('/path/to/cwd')
        );
    }

    public function testInstallProductionMode() {
        $this->assertNull($this->client->install('/path/to/project', false));
        Phony::inOrder(
                $this->executableFinder->find->calledWith('bower', 'node_modules/.bin/bower'), $this->chdir->calledWith('/path/to/project'), $this->processExecutor->execute->calledWith("'/path/to/bower' 'install' '--production'"), $this->chdir->calledWith('/path/to/cwd')
        );
    }

    public function testInstallFailureBowerNotFound() {
        $this->executableFinder->find('bower', 'node_modules/.bin/bower')->returns(null);

        $this->setExpectedException('Peertopark\Composer\BowerBridge\Exception\BowerNotFoundException');
        $this->client->install('/path/to/project');
    }

    public function testInstallFailureCommandFailed() {
        $this->processExecutor->execute('*')->returns(1);

        $this->setExpectedException('Peertopark\Composer\BowerBridge\Exception\BowerCommandFailedException');
        $this->client->install('/path/to/project');
    }

    public function testUpdate() {
        $this->assertNull($this->client->update('/path/to/project'));
        $this->assertNull($this->client->update('/path/to/project'));
        Phony::inOrder(
                $this->executableFinder->find->calledWith('bower', 'node_modules/.bin/bower'), $this->chdir->calledWith('/path/to/project'), $this->processExecutor->execute->calledWith("'/path/to/bower' 'update'"), $this->chdir->calledWith('/path/to/cwd'), $this->chdir->calledWith('/path/to/project'), $this->processExecutor->execute->calledWith("'/path/to/bower' 'update'"), $this->chdir->calledWith('/path/to/cwd')
        );
    }

    public function testUpdateFailureBowerNotFound() {
        $this->executableFinder->find('bower', 'node_modules/.bin/bower')->returns(null);

        $this->setExpectedException('Peertopark\Composer\BowerBridge\Exception\BowerNotFoundException');
        $this->client->update('/path/to/project');
    }

    public function testUpdateFailureCommandFailed() {
        $this->processExecutor->execute('*')->returns(1);

        $this->setExpectedException('Peertopark\Composer\BowerBridge\Exception\BowerCommandFailedException');
        $this->client->update('/path/to/project');
    }

}
