<?php

namespace Mackerel\Objects;

class Host extends AbstractObject
{
    const MACKEREL_INTERFACE_NAME_PATTERN = '/^eth\d/';

    /**
     * @var array
     * @deprecated
     */
    public $hash;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $meta;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $memo;

    /**
     * @var bool
     */
    public $isRetired;

    /**
     * @var string
     */
    public $id;

    /**
     * @var int
     */
    public $createdAt;

    /**
     * @var array
     */
    public $roles;

    /**
     * @var array
     */
    public $interfaces;

    /**
     * @param array $args
     */
    public function __construct(array $args = [])
    {
        $this->hash = $args;
        parent::__construct($args);
    }

    /**
     * @return string|null
     */
    public function ipAddr()
    {
        foreach ($this->interfaces as $interface) {
            if (preg_match(self::MACKEREL_INTERFACE_NAME_PATTERN, $interface['name'])) {
                return $interface['ipAddress'];
            }

            return null;
        }
    }

    /**
     * @return string|null
     */
    public function macAddr()
    {
        foreach ($this->interfaces as $interface) {
            if (preg_match(self::MACKEREL_INTERFACE_NAME_PATTERN, $interface['name'])) {
                return $interface['macAddress'];
            }

            return null;
        }
    }

    /**
     * @return array
     * @deprecated
     */
    public function toHash()
    {
        return $this->toH();
    }
}
