<?php

namespace app\types;

class Email
{
    private string $localPart;
    private string $domain;
    private string $extension;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address");
        }

        [$this->localPart, $domainAndExtension] = explode('@', $email);
        [$this->domain, $this->extension] = explode('.', $domainAndExtension);
    }

    public function __toString(): string
    {
        return $this->localPart . '@' . $this->domain . '.' . $this->extension;
    }

    public function getLocalPart(): string
    {
        return $this->localPart;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

}
