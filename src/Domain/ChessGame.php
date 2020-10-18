<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Events\DomainEvent;
use App\Domain\Events\GameCreated;
use App\Domain\Events\GaveUp;
use App\Domain\Events\MoveMade;
use App\Domain\Exceptions\GameEndedException;
use App\Domain\Exceptions\GameNotStartedException;
use App\Domain\Exceptions\MoveNotAllowedException;
use App\Domain\Exceptions\UnknownEventException;
use App\Domain\Exceptions\WrongPlayerException;
use App\Domain\Services\ChessEngine;
use Ramsey\Uuid\Uuid;

class ChessGame
{
    private ChessEngine $chessEngine;
    /**
     * @var array<DomainEvent>
     */
    private array $unpublishedEvents;

    private string $id;
    private string $playerName;
    private string $playerTwoName;
    private string $gameName;
    private bool $gameStarted = false;
    private ?string $lastMoveBy = null;
    private ?string $gameGaveUpBy = null;

    public function __construct(ChessEngine $chessEngine)
    {
        $this->chessEngine = $chessEngine;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function makeMove(string $player, string $move): bool
    {
        if (!$this->gameStarted) {
            throw new GameNotStartedException('Can not make move no started game');
        }

        if ($this->chessEngine->isGameOver() || $this->gameGaveUpBy) {
            throw new GameEndedException('Game has already ended');
        }
        if (!in_array($move, $this->chessEngine->getAvailableMoves())) {
            throw new MoveNotAllowedException(sprintf("Move '%s' not permitted. Has to be one of '%s'", $move, implode(',', $this->chessEngine->getAvailableMoves())));
        }
        if ($player !== $this->hasNextMove()) {
            throw new WrongPlayerException(sprintf('It is %s turn. Not %s', $this->hasNextMove(), $player));
        }

        $makeMoveEvent = new MoveMade(
            $player,
            $move
        );

        $this->unpublishedEvents[] = $makeMoveEvent;
        $this->apply($makeMoveEvent);

        echo $this->chessEngine->display();

        return $this->chessEngine->isGameOver();
    }

    private function hasNextMove(): string
    {
        if (!$this->lastMoveBy) {
            return $this->playerName;
        } else {
            return $this->lastMoveBy === $this->playerName
                ? $this->playerTwoName
                : $this->playerName;
        }
    }

    public function giveUp(string $player): void
    {
        if ($this->chessEngine->isGameOver() || $this->gameGaveUpBy) {
            throw new GameEndedException('Game has already ended');
        }

        if (!$this->gameStarted) {
            throw new GameNotStartedException('Can not give up not started game');
        }

        $gaveUpEvent = new GaveUp(
            $player
        );

        $this->unpublishedEvents[] = $gaveUpEvent;
        $this->apply($gaveUpEvent);

        echo $this->chessEngine->display();
    }

    public function create(
        string $playerName,
        string $playerTwoName,
        ?string $gameName): void
    {
        $createEvent = new GameCreated(
            (string) Uuid::uuid4(),
            (string) new PlayerName($playerName),
            (string) new PlayerName($playerTwoName),
            (string) new GameName($gameName)
        );
        $this->unpublishedEvents[] = $createEvent;
        $this->apply($createEvent);

        echo $this->chessEngine->display();
    }

    /**
     * @return array<DomainEvent>
     */
    public function getUnpublishedEvents(): array
    {
        return $this->unpublishedEvents;
    }

    public function apply(DomainEvent $event): void
    {
        if (GameCreated::class === get_class($event)) {
            $this->handleGameCreated($event);
        } elseif (MoveMade::class === get_class($event)) {
            $this->handleMoveMade($event);
        } elseif (GaveUp::class === get_class($event)) {
            $this->handleGaveUp($event);
        } else {
            throw new UnknownEventException(sprintf('Event of type %s can is not handled', get_class($event)));
        }
    }

    private function handleGameCreated(GameCreated $event): void
    {
        $this->id = $event->getId();
        $this->playerName = $event->getPlayerName();
        $this->playerTwoName = $event->getPlayerTwoName();
        $this->gameName = $event->getGameName();
        $this->gameStarted = true;
    }

    private function handleMoveMade(MoveMade $event): void
    {
        $this->chessEngine->makeMove($event->getMove());
        $this->lastMoveBy = $event->getPlayerName();
    }

    private function handleGaveUp(GaveUp $event): void
    {
        $this->gameGaveUpBy = $event->getPlayerName();

        echo sprintf('Player %s has gave up the game', $event->getPlayerName()).PHP_EOL;
    }
}
