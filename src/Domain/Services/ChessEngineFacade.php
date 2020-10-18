<?php

declare(strict_types=1);

namespace App\Domain\Services;

use Ryanhs\Chess\Chess;

class ChessEngineFacade implements ChessEngine
{
    private Chess $chessEngine;

    public function __construct(Chess $chessEngine)
    {
        $this->chessEngine = $chessEngine;
    }

    public function makeMove(string $move): void
    {
        $this->chessEngine->move($move);
    }

    /**
     * @return array<string>
     */
    public function getAvailableMoves(): array
    {
        return $this->chessEngine->moves();
    }

    public function isGameOver(): bool
    {
        return $this->chessEngine->gameOver();
    }

    public function display(): string
    {
        return $this->chessEngine->ascii();
    }
}
