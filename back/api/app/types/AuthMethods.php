<?php
declare(strict_types=1);
namespace app\types;
enum AuthMethods: string {
    case BEARER = "BEARER";
    case BASIC = "BASIC";
    case UNSUPPORTED = "UNSUPPORTED";
    case NONE = "NONE";
}
