<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Factory;

use BitBag\SyliusElasticsearchPlugin\EventListener\QueryCreatedEventInterface;
use Elastica\Query\AbstractQuery;

interface QueryCreatedEventFactoryInterface
{
    public function createNewEvent(AbstractQuery $boolQuery): QueryCreatedEventInterface;
}
