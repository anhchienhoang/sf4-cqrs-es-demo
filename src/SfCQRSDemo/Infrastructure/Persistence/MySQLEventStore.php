<?php

namespace SfCQRSDemo\Infrastructure\Persistence;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use JMS\Serializer\SerializerInterface;
use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\DomainEvent;
use SfCQRSDemo\Shared\DomainEvents;
use SfCQRSDemo\Shared\DomainEventsHistory;
use SfCQRSDemo\Shared\EventStore;

class MySQLEventStore implements EventStore
{
    const TABLE_NAME = 'events';

    protected $connection;

    protected $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function append(DomainEvents $events)
    {
        $stmt = $this->connection->prepare(
            sprintf('INSERT INTO %s (`aggregate_id`, `event_name`, `created_at`, `payload`) VALUES (:aggregateId, :eventName, :createdAt, :payload)', static::TABLE_NAME)
        );

        /** @var DomainEvent $event */
        foreach ($events as $event) {
            $stmt->execute([
                ':aggregateId' => (string) $event->getAggregateId(),
                ':eventName' => get_class($event),
                ':createdAt' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                ':payload' => $this->serializer->serialize($event, 'json'),
            ]);
        }
    }

    public function get(AggregateId $aggregateId): DomainEventsHistory
    {
        $stmt = $this->connection->prepare(sprintf('SELECT * FROM %s WHERE aggregate_id=:aggregateId', static::TABLE_NAME));
        $stmt->execute([':aggregateId' => (string) $aggregateId]);

        $events = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $events[] = $this->serializer->deserialize($row['payload'], $row['event_name'], 'json');
        }

        $stmt->closeCursor();

        return new DomainEventsHistory($aggregateId, $events);
    }
}
