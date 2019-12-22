<?php


namespace Mackerel\Objects;


abstract class AbstractObject
{
    /**
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