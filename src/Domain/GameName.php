<?php

namespace App\Domain;

final class GameName
{
    private const MAX_NAME_LEN = 16;
    private const DEFAULT_GAME_NAME = 'Test chess game';

    private string $name;

    public function __construct(?string $name)
    {
        $gameName = $name ?? self::DEFAULT_GAME_NAME;

        if (mb_strlen($gameName) <= self::MAX_NAME_LEN) {
            $this->name = $gameName;
        } else {
            throw new \Exception(sprintf('Provided game name is too long. Max len is %d', self::MAX_NAME_LEN));
        }
    }

    public function __toString()
    {
        return $this->name;
    }
}
