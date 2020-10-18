<?php

declare(strict_types=1);

namespace App\Domain\Services;

interface ChessEngine
{
    public function makeMove(string $move): void;

    /**
     * @return array<string>
     */
    public function getAvailableMoves(): array;

    public function isGameOver(): bool;

    public function display(): string;
}
