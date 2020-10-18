<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore\PostgreSQL;

use App\Domain\Events\DomainEvent;
use App\Infrastructure\EventStore\Exceptions\InvalidEventMappingException;
use App\Infrastructure\EventStore\Exceptions\UnknownEventTypeException;
use App\Infrastructure\EventStore\PersistentEvent;
use App\Infrastructure\EventStore\PostgreSQL\PersistentEvent as PostgreSQLPersistentEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class EventStore extends ServiceEntityRepository implements \App\Infrastructure\EventStore\EventStore
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostgreSQLPersistentEvent::class);
    }

    /**
     * @param DomainEvent ...$events
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveStream(string $aggregateId, DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $persistentEvent = $this->enrich($aggregateId, $event);
            $this->getEntityManager()->persist($persistentEvent);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<DomainEvent>
     */
    public function getStream(string $aggregateId): array
    {
        $events = $this->findBy(['aggregateId' => $aggregateId]);

        $domainEvents = [];
        foreach ($events as $event) {
            $domainEvents[] = $this->extract($event);
        }

        return $domainEvents;
    }

    private function enrich(string $aggregateId, DomainEvent $domainEvent): PersistentEvent
    {
        $eventType = array_search(get_class($domainEvent), self::EVENT_MAP);

        if (!$eventType) {
            throw new UnknownEventTypeException(sprintf('Unknown event type can not be handled: %s', $eventType));
        }

        return (new PostgreSQLPersistentEvent())
            ->setAggregateId($aggregateId)
            ->setType($eventType)
            ->setDate(new \DateTimeImmutable('now'))
            ->setPayload($domainEvent->toArray());
    }

    private function extract(PersistentEvent $event): DomainEvent
    {
        $eventClass = self::EVENT_MAP[$event->getType()] ?? null;

        if (!$eventClass) {
            throw new UnknownEventTypeException(sprintf('Unknown event type can not be handled: %s', $event->getType()));
        }

        if (is_a($eventClass, DomainEvent::class)) {
            throw new InvalidEventMappingException(sprintf("Invalid event mapped to: '%s'. Has to be of type '%s'", $eventClass, DomainEvent::class));
        }

        return $eventClass::fromArray($event->getPayload());
    }
}
