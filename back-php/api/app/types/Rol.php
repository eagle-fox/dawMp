<?php

namespace app\types;

enum Rol: string {
    case ADMIN = "ADMIN";
    case USER = "USER";
    case GUEST = "GUEST";
    case IOT = "IOT";

    public function getValue(): string
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function equals(Rol $targetRol): bool
    {
        return $this->value === $targetRol->getValue();
    }

}
