<?php

declare(strict_types=1);

namespace App\Domain\Events;

class MoveMade implements DomainEvent
{
    private const PLAYER_NAME_KEY = 'playerName';
    private const MOVE_KEY = 'move';

    private string $playerName;
    private string $move;

    public function __construct(
        string $playerName,
        string $move
    ) {
        $this->playerName = $playerName;
        $this->move = $move;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function getMove(): string
    {
        return $this->move;
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return [
            self::PLAYER_NAME_KEY => $this->playerName,
            self::MOVE_KEY => $this->move,
        ];
    }

    /**
     * @param array<string> $data
     */
    public static function fromArray(array $data): DomainEvent
    {
        return new self(
            $data[self::PLAYER_NAME_KEY],
            $data[self::MOVE_KEY]
        );
    }
}
