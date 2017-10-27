<?php

namespace Attogram\SharedMedia\Api;

use Psr\Log\AbstractLogger;

/**
 * Simple PSR3 Logger
 */
class Logger extends AbstractLogger
{
    const VERSION = '0.10.0';

    private $level;
    private $levelKey;

    private $levels = [
        0 => 'EMERGENCY',
        1 => 'ALERT',
        2 => 'CRITICAL',
        3 => 'ERROR',
        4 => 'WARNING',
        5 => 'NOTICE',
        6 => 'INFO',
        7 => 'DEBUG'
    ];

    public function __construct($level = null)
    {
        $this->setLevel($level);
    }

    public function setLevel($level = null)
    {
        if (!$level || !in_array(strtoupper($level), $this->levels)) {
            $this->level = null;
            return;
        }
        $this->level = strtoupper($level);
        $this->levelKey= array_search($this->level, $this->levels);
    }

    public function isLevel($level)
    {
        $currentLevelKey= array_search(strtoupper($level), $this->levels);
        if ($currentLevelKey <= $this->levelKey) {
            return true;
        }
        return false;
    }

    public function log($level, $message, array $context = [])
    {
        if (!$this->isLevel($level)) {
            return;
        }
        $level = strtoupper($level);
        $out = '<div class="log">'."$level: $message";
        if (!empty($context)) {
            $out .= ' '.htmlentities(json_encode($context));
        }
        $out .= '</div>';
        print $out;
    }
}