<?php
/*

This file was created by developers working at BitBag

Do you need more information about us and what we do? Visit our   website!

We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Factory;

use BitBag\SyliusElasticsearchPlugin\EventListener\QueryCreatedEvent;
use BitBag\SyliusElasticsearchPlugin\EventListener\QueryCreatedEventInterface;
use Elastica\Query\AbstractQuery;

final class QueryCreatedEventFactory implements QueryCreatedEventFactoryInterface
{
    public function createNewEvent(AbstractQuery $boolQuery): QueryCreatedEventInterface
    {
        return new QueryCreatedEvent($boolQuery);
    }
}
