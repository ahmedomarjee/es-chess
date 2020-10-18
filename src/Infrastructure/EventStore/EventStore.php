<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use App\Domain\Events\DomainEvent;
use App\Domain\Events\GameCreated;
use App\Domain\Events\GaveUp;
use App\Domain\Events\MoveMade;

interface EventStore
{
    const EVENT_MAP = [
        'GameCreated' => GameCreated::class,
        'MoveMade' => MoveMade::class,
        'PlayerGaveUp' => GaveUp::class,
    ];

    /**
     * @return array<DomainEvent>
     */
    public function getStream(string $aggregateId): array;

    /**
     * @param DomainEvent ...$events
     */
    public function saveStream(string $aggregateId, DomainEvent ...$events): void;
}
