<?php

namespace app\types;

enum Rol: string {
    case ADMIN = "ADMIN";
    case USER = "USER";
    case GUEST = "GUEST";
    case IOT = "IOT";
}
