<?php

namespace Mackerel;

class Host
{
    const MACKEREL_INTERFACE_NAME_PATTERN = '/^eth\d/';

    /**
     * @var array
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
     * Host constructor.
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->hash = $args;
        $this->name = $args['name'];
        $this->meta = $args['meta'];
        $this->type = $args['type'];
        $this->status = $args['status'];
        $this->memo = $args['memo'];
        $this->isRetired = $args['isRetired'];
        $this->id = $args['id'];
        $this->createdAt = $args['createdAt'];
        $this->roles = $args['roles'];
        $this->interfaces = $args['interfaces'];
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
     */
    public function toHash()
    {
        return $this->hash;
    }
}
