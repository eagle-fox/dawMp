<?php

namespace app\types;

class IPv4
{
    private string $ipv4;

    public function __construct($ipv4)
    {
        if (!filter_var($ipv4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new \InvalidArgumentException("Invalid IPv4 address");
        }

        $this->ipv4 = $ipv4;
    }

    public function getIPv4(): string
    {
        return $this->ipv4;
    }

    public function __toString(): string
    {
        return $this->ipv4;
    }

}
