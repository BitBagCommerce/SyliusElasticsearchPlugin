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
use Elastica\Query\Term;

final class HasAttributesQueryBuilder implements QueryBuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): ?AbstractQuery
    {
        $attributeQuery = new BoolQuery();

        foreach ((array) $data['attribute_values'] as $attributeValue) {
            $termQuery = new Term();
            $termQuery->setTerm($data['attribute'], $attributeValue);
            $attributeQuery->addShould($termQuery);
        }

        return $attributeQuery;
    }
}
