<?php
/*

This file was created by developers working at BitBag

Do you need more information about us and what we do? Visit our   website!

We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
  */

namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use Elastica\Query\AbstractQuery;
use ECSPrefix20211002\Psr\EventDispatcher\StoppableEventInterface;

interface QueryCreatedEventInterface extends StoppableEventInterface
{
    public const NAME = 'query.created.event';

    public function getQuery(): AbstractQuery;
}
