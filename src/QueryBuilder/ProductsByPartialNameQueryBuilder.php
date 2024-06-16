<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;

final class ProductsByPartialNameQueryBuilder implements QueryBuilderInterface
{
    public function __construct(
        private QueryBuilderInterface $containsNameQueryBuilder
    ) {
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $nameQuery = $this->containsNameQueryBuilder->buildQuery($data);

        if (null !== $nameQuery) {
            $boolQuery->addFilter($nameQuery);
        }

        return $boolQuery;
    }
}
