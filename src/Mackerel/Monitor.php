<?php

namespace Mackerel;

class Monitor
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $duration;

    /**
     * @var string
     */
    public $metric;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $service;

    /**
     * @var int
     */
    public $maxCheckAttempts;

    /**
     * @var string
     */
    public $operator;

    /**
     * @var float
     */
    public $warning;

    /**
     * @var float
     */
    public $critical;

    /**
     * @var int
     */
    public $responseTimeWarning;

    /**
     * @var int
     */
    public $responseTimeCritical;

    /**
     * @var int
     */
    public $responseTimeDuration;

    /**
     * @var int
     */
    public $certificationExpirationWarning;

    /**
     * @var int
     */
    public $certificationExpirationCritical;

    /**
     * @var string
     */
    public $containsString;

    /**
     * @var string
     */
    public $expression;

    /**
     * @var int
     */
    public $notificationInterval;

    /**
     * @var array
     */
    public $scopes;

    /**
     * @var array
     */
    public $excludeScopes;

    /**
     * @var bool
     */
    public $isMute;

    /**
     * Monitor constructor.
     * @param array $args
     */
    public function __construct(array $args = [])
    {
        foreach (array_keys($this->toH()) as $k) {
            if (array_key_exists($k, $args)) {
                $this->$k = $args[$k];
            }
        }
    }

    /**
     * @return array
     */
    public function toH()
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toH());
    }
}