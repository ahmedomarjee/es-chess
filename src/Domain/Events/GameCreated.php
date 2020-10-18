<?php

declare(strict_types=1);

namespace App\Domain\Events;

class GameCreated implements DomainEvent
{
    private const ID_KEY = 'id';
    private const PLAYER_NAME_KEY = 'playerName';
    private const PLAYER_TWO_NAME_KEY = 'playerTwoName';
    private const GAME_NAME_KEY = 'gameName';

    private string $id;
    private string $playerName;
    private string $playerTwoName;
    private string $gameName;

    public function __construct(
        string $id,
        string $playerName,
        string $playerTwoName,
        string $gameName
    ) {
        $this->id = $id;
        $this->playerName = $playerName;
        $this->playerTwoName = $playerTwoName;
        $this->gameName = $gameName;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function getPlayerTwoName(): string
    {
        return $this->playerTwoName;
    }

    public function getGameName(): string
    {
        return $this->gameName;
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return [
            self::ID_KEY => $this->id,
            self::PLAYER_NAME_KEY => $this->playerName,
            self::PLAYER_TWO_NAME_KEY => $this->playerTwoName,
            self::GAME_NAME_KEY => $this->gameName,
        ];
    }

    /**
     * @param array<string> $data
     */
    public static function fromArray(array $data): DomainEvent
    {
        return new self(
            $data[self::ID_KEY],
            $data[self::PLAYER_NAME_KEY],
            $data[self::PLAYER_TWO_NAME_KEY],
            $data[self::GAME_NAME_KEY]
        );
    }
}
