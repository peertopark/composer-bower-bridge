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

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Peertopark\Composer\BowerBridge\Exception\BowerCommandFailedException;
use Peertopark\Composer\BowerBridge\Exception\BowerNotFoundException;

/**
 * Manages NPM installs, updates, and shrinkwrapping for Composer projects.
 */
class BowerBridge {

    private $io;
    private $vendorFinder;
    private $client;

    /**
     * Construct a new Composer NPM bridge plugin.
     *
     * @access private
     *
     * @param IOInterface     $io           The i/o interface to use.
     * @param BowerVendorFinder $vendorFinder The vendor finder to use.
     * @param BowerClient       $client       The NPM client to use.
     */
    public function __construct(IOInterface $io, BowerVendorFinder $vendorFinder, BowerClient $client) {
        $this->io = $io;
        $this->vendorFinder = $vendorFinder;
        $this->client = $client;
    }

    /**
     * Install NPM dependencies for a Composer project and its dependencies.
     *
     * @param Composer $composer  The main Composer object.
     * @param boolean  $isDevMode True if dev mode is enabled.
     *
     * @throws BowerNotFoundException      If the npm executable cannot be located.
     * @throws BowerCommandFailedException If the operation fails.
     */
    public function install(Composer $composer, $isDevMode = true) {
        $this->io->write('<info>Installing Bower dependencies for root project</info>');

        if ($this->isDependantPackage($composer->getPackage(), $isDevMode)) {
            $this->client->install(null, $isDevMode);
        } else {
            $this->io->write('Nothing to install');
        }
        $this->installForVendors($composer);
    }

    /**
     * Update NPM dependencies for a Composer project and its dependencies.
     *
     * This will update and shrinkwrap the NPM dependencies of the main project.
     * It will also install any NPM dependencies of the main project's Composer
     * dependencies.
     *
     * @param Composer $composer The main Composer object.
     *
     * @throws BowerNotFoundException      If the npm executable cannot be located.
     * @throws BowerCommandFailedException If the operation fails.
     */
    public function update(Composer $composer) {
        $this->io->write('<info>Updating Bower dependencies for root project</info>');
        if ($this->isDependantPackage($composer->getPackage(), true)) {
            $this->client->update();
            $this->client->install(null, true);
        } else {
            $this->io->write('Nothing to update');
        }

        $this->installForVendors($composer);
    }

    /**
     * Returns true if the supplied package requires the Composer NPM bridge.
     *
     * @param PackageInterface $package                The package to inspect.
     * @param boolean          $includeDevDependencies True if the dev dependencies should also be inspected.
     *
     * @return boolean True if the package requires the bridge.
     */
    public function isDependantPackage(PackageInterface $package, $includeDevDependencies = false) {
        foreach ($package->getRequires() as $link) {
            if ('peertopark/composer-bower-bridge' === $link->getTarget()) {
                return true;
            }
        }
        if ($includeDevDependencies) {
            foreach ($package->getDevRequires() as $link) {
                if ('peertopark/composer-bower-bridge' === $link->getTarget()) {
                    return true;
                }
            }
        }

        return false;
    }

    private function installForVendors($composer) {
        $this->io->write(
                '<info>Installing Bower dependencies for Composer dependencies</info>'
        );
        $packages = $this->vendorFinder->find($composer, $this);

        if (count($packages) > 0) {
            foreach ($packages as $package) {
                $this->io->write(
                        sprintf(
                                '<info>Installing Bower dependencies for %s</info>', $package->getPrettyName()
                        )
                );

                $this->client->install($composer->getInstallationManager()->getInstallPath($package), false);
            }
        } else {
            $this->io->write('Nothing to install');
        }
    }

}
