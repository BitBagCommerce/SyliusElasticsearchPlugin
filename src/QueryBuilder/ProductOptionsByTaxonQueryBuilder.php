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

final class ProductOptionsByTaxonQueryBuilder implements QueryBuilderInterface
{
    private QueryBuilderInterface $hasTaxonQueryBuilder;

    public function __construct(QueryBuilderInterface $hasTaxonQueryBuilder)
    {
        $this->hasTaxonQueryBuilder = $hasTaxonQueryBuilder;
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $boolQuery = new BoolQuery();
        $taxonQuery = $this->hasTaxonQueryBuilder->buildQuery($data);

        $boolQuery->addMust($taxonQuery);

        return $boolQuery;
    }
}
