<?php

namespace BitBag\SyliusElasticsearchPlugin\Factory;

use BitBag\SyliusElasticsearchPlugin\EventListener\QueryCreatedEvent;
use BitBag\SyliusElasticsearchPlugin\EventListener\QueryCreatedEventInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;

interface QueryCreatedEventFactoryInterface
{
    public function createNewEvent(AbstractQuery $boolQuery): QueryCreatedEventInterface;
}
