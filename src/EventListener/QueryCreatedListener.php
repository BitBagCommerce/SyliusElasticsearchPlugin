<?php
/*

This file was created by developers working at BitBag

Do you need more information about us and what we do? Visit our   website!

We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\EventListener;



use Elastica\Query;


final class QueryCreatedListener
{

    public static function getSubscribedEvents()
    {
        return [
            QueryCreatedEvent::NAME => 'onQueryCreated',
        ];
    }

    public function onQueryCreated(Query\BoolQuery $query)
    {

    }
}
