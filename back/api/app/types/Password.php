<?php

namespace app\types;

use InvalidArgumentException;

class Password
{
    private string $hashedPassword;
    private string $plainPassword;
    private bool $debug;
    private bool $strongPassword;

    public function __construct(string $plainPassword)
    {
        $this->debug = getenv("LEAF_DEV_TOOLS") === "true";
        if ($this->debug) {
            $this->plainPassword = $plainPassword;
        }
        $this->strongPassword = getenv("STRONG_PASSWORD") === "true";
        if ($this->strongPassword) {
            $this->validatePassword($plainPassword);
        }
        $this->hashedPassword = hash('sha256', $plainPassword);
    }

    private function validatePassword(string $password): void
    {
        if (strlen($password) < 12) {
            throw new InvalidArgumentException("Password must be at least 12 characters long");
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new InvalidArgumentException("Password must contain at least one uppercase letter");
        }

        if (!preg_match('/[a-z]/', $password)) {
            throw new InvalidArgumentException("Password must contain at least one lowercase letter");
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new InvalidArgumentException("Password must contain at least one number");
        }

        if (!preg_match('/\W/', $password)) {
            throw new InvalidArgumentException("Password must contain at least one special character");
        }

    }

    public function getPassword(): string
    {
        if ($this->debug) {
            return $this->hashedPassword;
        }
        return "Unauthorized access to hashed password. Debug mode is off.";
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    public function __toString(): string
    {
        return $this->hashedPassword;
    }

}
