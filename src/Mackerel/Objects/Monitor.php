<?php

namespace Mackerel\Objects;

class Monitor extends AbstractObject
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
}