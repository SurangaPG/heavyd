<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command;

use Symfony\Component\Console\Command\Command;

abstract class AbstractHeavydCommandBase extends Command {

  /**
   * @return \surangapg\Heavyd\HeavydApplication
   */
  function getApplication() {
    return parent::getApplication();
  }
}