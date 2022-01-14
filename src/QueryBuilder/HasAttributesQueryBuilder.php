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
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class HasAttributesQueryBuilder implements QueryBuilderInterface
{
    private $localeContext;

    public function __construct(LocaleContextInterface $localeContext)
    {
        $this->localeContext = $localeContext;
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $attributeQuery = new BoolQuery();

        foreach ((array) $data['attribute_values'] as $attributeValue) {
            $termQuery = new Term();
            $attribute = \sprintf('%s_%s', $data['attribute'], $this->localeContext->getLocaleCode());
            $termQuery->setTerm($attribute, $attributeValue);
            $attributeQuery->addShould($termQuery);
        }

        return $attributeQuery;
    }
}
