<?php

namespace app\types;

use JsonSerializable;
use Random\RandomException;

class UUID implements JsonSerializable
{
    private string $uuid;

    public function __construct($uuid = null)
    {
        if ($uuid !== null) {
            if (!$this->isValidUUID($uuid)) {
                throw new \InvalidArgumentException("Invalid UUID");
            }
            $this->uuid = $uuid;
        } else {
            $this->uuid = $this->generate();
        }
    }

    /**
     * For simplicity, we use a 32 character UUID as a token.
     * @param $length int our DB is RFC 4122 compliant so we use 32
     * @return string
     * @throws RandomException
     */
    private function generate($length = 32): string
    {
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4));
    }

    private function isValidUUID($uuid): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid) === 1;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function __toString()
    {
        return $this->uuid;
    }

    public function jsonSerialize(): string
    {
        return $this->uuid;
    }
}
