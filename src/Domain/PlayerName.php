<?php

namespace App\Domain;

final class PlayerName
{
    private const MAX_NAME_LEN = 32;
    private string $name;

    public function __construct(string $name)
    {
        if (mb_strlen($name) <= self::MAX_NAME_LEN) {
            $this->name = $name;
        } else {
            throw new \Exception(sprintf('Provided player name is too long. Max len is %d', self::MAX_NAME_LEN));
        }
    }

    public function __toString()
    {
        return $this->name;
    }
}
