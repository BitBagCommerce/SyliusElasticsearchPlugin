<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Query\AbstractQuery;

interface FacetInterface
{
    public function getAggregation(): AbstractAggregation;

    public function getQuery(array $selectedBuckets): AbstractQuery;

    public function getBucketLabel(array $bucket): string;

    public function getLabel(): string;
}
