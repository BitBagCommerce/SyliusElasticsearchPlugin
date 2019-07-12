<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Query\AbstractQuery;

interface FacetInterface
{
    public function getAggregation(): AbstractAggregation;
    public function getQuery(array $selectedBuckets): AbstractQuery;
    public function getBucketLabel(array $bucket): string;
}
