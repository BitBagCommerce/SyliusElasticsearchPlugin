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
use Elastica\Query\Term;
use function sprintf;

class AttributesTypeNumberQueryBuilder implements AttributesQueryBuilderCollectorInterface
{
    private const AVAILABLE_ATTRIBUTES_TYPE = [
        'percent',
        'integer',
        'checkbox',
    ];

    public function supports(string $type): bool
    {
        return in_array($type, self::AVAILABLE_ATTRIBUTES_TYPE, true);
    }

    public function buildQuery(array $data, string $localCode): BoolQuery
    {
        $attributeQuery = new BoolQuery();

        foreach ((array) $data['attribute_values'] as $attributeValue) {
            $termQuery = new Term();
            $attribute = sprintf('%s_%s', $data['attribute'], $localCode);
            $termQuery->setTerm($attribute, $attributeValue);
            $attributeQuery->addShould($termQuery);
        }

        return $attributeQuery;
    }
}
