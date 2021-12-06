<?php
/*

This file was created by developers working at BitBag

Do you need more information about us and what we do? Visit our   website!

We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Notifier;

use BitBag\SyliusElasticsearchPlugin\EventListener\QueryCreatedEventInterface;
use BitBag\SyliusElasticsearchPlugin\Factory\QueryCreatedEventFactoryInterface;
use Elastica\Query\AbstractQuery;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class QueryDispatcher implements QueryDispatcherInterface
{
    /** @var QueryCreatedEventFactoryInterface */
    private $factory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        QueryCreatedEventFactoryInterface $factory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatchNewQuery(AbstractQuery $boolQuery): QueryCreatedEventInterface
    {
        $event = $this->factory->createNewEvent($boolQuery);
        $this->eventDispatcher->dispatch($event);

        return $event;
    }
}
