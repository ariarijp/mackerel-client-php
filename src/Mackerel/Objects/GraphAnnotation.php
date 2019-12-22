<?php

namespace Mackerel\Objects;

class GraphAnnotation extends AbstractObject
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $from;

    /**
     * @var int
     */
    public $to;

    /**
     * @var string
     */
    public $service;

    /**
     * @var array
     */
    public $roles;
}