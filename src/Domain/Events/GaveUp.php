<?php

declare(strict_types=1);

namespace App\Domain\Events;

class GaveUp implements DomainEvent
{
    private const PLAYER_NAME_KEY = 'playerName';

    private string $playerName;

    public function __construct(
        string $playerName
    ) {
        $this->playerName = $playerName;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return [
            self::PLAYER_NAME_KEY => $this->playerName,
        ];
    }

    /**
     * @param array<string> $data
     */
    public static function fromArray(array $data): DomainEvent
    {
        return new self(
            $data[self::PLAYER_NAME_KEY]
        );
    }
}
