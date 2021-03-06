<?php
/**
 * @contains
 * Class to handle bin commands that couldn't be run.
 */
namespace surangapg\Heavyd\Components\BinRunner\Exception;

use surangapg\Heavyd\Components\BinRunner\BinRunnerInterface;

/**
 * Class BinRunFailedException
 *
 * Exception to throw when a bin command couldn't be run.
 *
 * @package workflow\Workflow\Components\Exception
 */
class BinRunFailedException extends \Exception {

  /**
   * BinRunFailedException constructor.
   *
   * @param \surangapg\Heavyd\Components\BinRunner\BinRunnerInterface $binRunner
   *   Runner that failed.
   * @param int $code
   *   The code to return
   * @param \Exception $previous
   *   Previous exception.
   */
  public function __construct(BinRunnerInterface $binRunner, $code = 0, \Exception $previous = null) {

    $message = 'Failed running bin command.' . PHP_EOL;
    $message .= 'Full command: ' . PHP_EOL;
    $message .= $binRunner->getFullCommand() . PHP_EOL;
    $message .= 'Full output: ' . PHP_EOL;
    $message .= $binRunner->getOutput();

    parent::__construct($message, $code, $previous);
  }

}