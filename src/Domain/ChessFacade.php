<?php

declare(strict_types=1);

namespace App\Domain;

use App\Infrastructure\EventStore\EventStore;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ChessFacade
{
    private ContainerInterface $container;
    private EventStore $eventStore;

    public function __construct(
        ContainerInterface $container,
        EventStore $eventStore
    ) {
        $this->container = $container;
        $this->eventStore = $eventStore;
    }

    public function makeMove(string $gameId, string $player, string $move): void
    {
        $chessGame = $this->container->get(ChessGame::class);

        $events = $this->eventStore->getStream($gameId);
        foreach ($events as $event) {
            $chessGame->apply($event);
        }
        $chessGame->makeMove($player, $move);
        $this->eventStore->saveStream($chessGame->getId(), ...$chessGame->getUnpublishedEvents());
    }

    public function createGame(string $playerName, string $playerTwoName, ?string $gameName): string
    {
        $chessGame = $this->container->get(ChessGame::class);

        $chessGame->create($playerName, $playerTwoName, $gameName);
        $this->eventStore->saveStream($chessGame->getId(), ...$chessGame->getUnpublishedEvents());

        return $chessGame->getId();
    }

    public function giveUp(string $gameId, string $playerName): void
    {
        $chessGame = $this->container->get(ChessGame::class);

        $events = $this->eventStore->getStream($gameId);
        foreach ($events as $event) {
            $chessGame->apply($event);
        }
        $chessGame->giveUp($playerName);
        $this->eventStore->saveStream($chessGame->getId(), ...$chessGame->getUnpublishedEvents());
    }
}
