<?php

namespace app\types;

enum Rol: string {
    case ADMIN = "ADMIN";
    case USER = "USER";
    case GUEST = "GUEST";
    case IOT = "IOT";



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
