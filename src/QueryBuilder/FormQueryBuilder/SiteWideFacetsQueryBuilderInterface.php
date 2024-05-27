<?php

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder\FormQueryBuilder;

use Elastica\Query;
use Symfony\Component\Form\FormEvent;

interface SiteWideFacetsQueryBuilderInterface
{
    public function getQuery(FormEvent $event): Query;
}
