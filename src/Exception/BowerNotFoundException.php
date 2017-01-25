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

/**
 * The npm executable could not be found.
 */
final class BowerNotFoundException extends Exception {

    /**
     * Construct a new npm not found exception.
     *
     * @param Exception|null $cause The cause, if available.
     */
    public function __construct(Exception $cause = null) {
        parent::__construct('The bower executable could not be found.', 0, $cause);
    }

}
