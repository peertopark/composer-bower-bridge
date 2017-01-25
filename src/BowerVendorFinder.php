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
use Composer\Package\PackageInterface;

/**
 * Finds NPM bridge enabled vendor packages.
 */
class BowerVendorFinder {

    /**
     * Find all NPM bridge enabled vendor packages.
     *
     * @param Composer  $composer The Composer object for the root project.
     * @param BowerBridge $bridge   The bridge to use.
     *
     * @return array<integer,PackageInterface> The list of NPM bridge enabled vendor packages.
     */
    public function find(Composer $composer, BowerBridge $bridge) {
        $packages = $composer->getRepositoryManager()->getLocalRepository()->getPackages();
        $dependantPackages = array();
        foreach ($packages as $package) {
            if ($bridge->isDependantPackage($package, false)) {
                $dependantPackages[] = $package;
            }
        }
        return $dependantPackages;
    }

}
