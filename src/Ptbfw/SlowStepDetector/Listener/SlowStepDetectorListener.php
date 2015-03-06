<?php

namespace Ptbfw\SlowStepDetector\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Behat\Behat\EventDispatcher\Event\StepTested;

/**
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class SlowStepDetectorListener implements EventSubscriberInterface
{

    private $slowStepTime;
    private $verboseLevel;
    private $startTime;

    /**
     * Initializes initializer.
     *
     * @param array $options
     */
    public function __construct($verboseLevel, $slowStepTime) {
        $this->verboseLevel = $verboseLevel;
        $this->slowStepTime = $slowStepTime;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
        return array(
            StepTested::BEFORE => array('stepStart', -100),
            StepTested::AFTER => array('stepEnd', -100),
        );
    }

    public function stepStart() {
        $this->startTime = microtime(true);
    }

    public function stepEnd() {
        $duration = microtime(true) - $this->startTime;
        if ($duration >= $this->slowStepTime || $this->verboseLevel >= 1) {
            echo '[ ' . $this->formatTime($duration) . ' s duration ] ';
        }
    }

    protected function formatTime($time) {
        return number_format($time, 2);
    }

}
