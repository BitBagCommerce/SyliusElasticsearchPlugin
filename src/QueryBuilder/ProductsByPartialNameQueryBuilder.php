<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;

final class ProductsByPartialNameQueryBuilder implements QueryBuilderInterface
{
    /** @var QueryBuilderInterface */
    private $containsNameQueryBuilder;

    public function __construct(QueryBuilderInterface $containsNameQueryBuilder)
    {
        $this->containsNameQueryBuilder = $containsNameQueryBuilder;
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
