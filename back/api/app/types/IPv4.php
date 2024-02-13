<?php

namespace app\types;

use JsonSerializable;

class IPv4 implements JsonSerializable
{
    private int $ipv4;

    public function __construct($ipv4)
    {
        if (is_string($ipv4)) {
            if (!filter_var($ipv4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                throw new \InvalidArgumentException("Invalid IPv4 address");
            }
            $this->ipv4 = ip2long($ipv4);
        } elseif (is_int($ipv4)) {
            $this->ipv4 = $ipv4;
        } else {
            throw new \InvalidArgumentException("Invalid argument type for IPv4 address");
        }
    }

    public function jsonSerialize(): string
    {
        return $this->getHumanReadable();
    }

    public function __toString(): string
    {
        return $this->ipv4;
    }

    public function getHumanReadable(): string
    {
        return long2ip($this->ipv4);
    }
}
