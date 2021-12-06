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
use Elastica\Query\Term;

final class HasOptionsQueryBuilder implements QueryBuilderInterface
{
    public function buildQuery(array $data): ?AbstractQuery
    {
        $optionQuery = new BoolQuery();

        foreach ((array) $data['option_values'] as $optionValue) {
            $termQuery = new Term();
            $termQuery->setTerm($data['option'], $optionValue);
            $optionQuery->addShould($termQuery);
        }

        return $optionQuery;
    }
}
