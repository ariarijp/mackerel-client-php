<?php

namespace Mackerel\Objects;

class GraphAnnotation
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

    /**
     * GraphAnnotation constructor.
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