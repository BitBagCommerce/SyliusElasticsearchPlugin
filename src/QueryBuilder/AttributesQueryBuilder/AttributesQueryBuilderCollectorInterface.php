<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder\AttributesQueryBuilder;

use Elastica\Query\BoolQuery;

interface AttributesQueryBuilderCollectorInterface
{
    public function supports(string $type): bool;

    public function buildQuery(array $data, string $localCode): BoolQuery;
}
