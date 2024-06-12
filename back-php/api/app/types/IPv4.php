<?php

namespace app\types;

use InvalidArgumentException;
use JsonSerializable;

class IPv4 implements JsonSerializable
{
    private int $ipv4;

    public function __construct(IPv4 | string | int $ipv4)
    {
        if ($ipv4 instanceof IPv4) {
            $this->ipv4 = $ipv4->ipv4;
            return;
        }

        if (is_numeric($ipv4) || is_int($ipv4)) {
            $ipv4 = long2ip(intval($ipv4));
        }

        if (is_string($ipv4) && filter_var($ipv4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->ipv4 = ip2long($ipv4);
            return;
        }

        throw new InvalidArgumentException("Invalid argument type for IPv4.js address");
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
