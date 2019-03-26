<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
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
