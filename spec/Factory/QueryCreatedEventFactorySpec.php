<?php

namespace spec\BitBag\SyliusElasticsearchPlugin\Factory;

use BitBag\SyliusElasticsearchPlugin\EventListener\QueryCreatedEvent;
use BitBag\SyliusElasticsearchPlugin\Factory\QueryCreatedEventFactory;
use Elastica\Query\AbstractQuery;
use PhpSpec\ObjectBehavior;

class QueryCreatedEventFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(QueryCreatedEventFactory::class);
    }
    function it_create_new_event(AbstractQuery $boolQuery)
    {
        return new QueryCreatedEvent($boolQuery->getWrappedObject());
    }
}
