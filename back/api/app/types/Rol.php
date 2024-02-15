<?php

namespace app\types;

enum Rol: string {
    case ADMIN = "ADMIN";
    case USER = "USER";
    case GUEST = "GUEST";
    case IOT = "IOT";

    public static function fromValue(string $rol): Rol
    {
        return match ($rol) {
            "ADMIN" => Rol::ADMIN,
            "USER" => Rol::USER,
            "IOT" => Rol::IOT,
            default => Rol::GUEST,
        };
    }


    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

}
