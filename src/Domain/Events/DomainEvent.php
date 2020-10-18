<?php

declare(strict_types=1);

namespace App\Domain\Events;

interface DomainEvent
{
    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): DomainEvent;

    /**
     * @return array<string>
     */
    public function toArray(): array;
}
