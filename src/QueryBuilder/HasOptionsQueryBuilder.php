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

final class HasOptionsQueryBuilder implements QueryBuilderInterface
{
    /**
     * {@inheritdoc}
     */
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
