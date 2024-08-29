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
use Webmozart\Assert\Assert;

final class TaxonProductsQueryBuilder implements QueryBuilderInterface
{
    public function __construct(
        private QueryBuilderInterface $isEnabledQueryBuilder,
        private QueryBuilderInterface $hasChannelQueryBuilder,
        private QueryBuilderInterface $containsNameQueryBuilder,
        private QueryBuilderInterface $hasTaxonQueryBuilder,
        private QueryBuilderInterface $hasOptionsQueryBuilder,
        private QueryBuilderInterface $hasAttributesQueryBuilder,
        private QueryBuilderInterface $hasPriceBetweenQueryBuilder,
        private string $optionPropertyPrefix,
        private string $attributePropertyPrefix
    ) {
    }

    public function buildQuery(array $data): AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $isEnabledQuery = $this->isEnabledQueryBuilder->buildQuery($data);
        $hasChannelQuery = $this->hasChannelQueryBuilder->buildQuery($data);

        Assert::notNull($isEnabledQuery);
        Assert::notNull($hasChannelQuery);

        $boolQuery->addMust($isEnabledQuery);
        $boolQuery->addMust($hasChannelQuery);

        $nameQuery = $this->containsNameQueryBuilder->buildQuery($data);
        $this->addMustIfNotNull($nameQuery, $boolQuery);

        $taxonQuery = $this->hasTaxonQueryBuilder->buildQuery($data);
        $this->addMustIfNotNull($taxonQuery, $boolQuery);

        $priceQuery = $this->hasPriceBetweenQueryBuilder->buildQuery($data);
        $this->addMustIfNotNull($priceQuery, $boolQuery);

        $this->resolveOptionQuery($boolQuery, $data);
        $this->resolveAttributeQuery($boolQuery, $data);

        return $boolQuery;
    }

    private function resolveOptionQuery(BoolQuery $boolQuery, array $data): void
    {
        foreach ($data as $key => $value) {
            if (0 === strpos($key, $this->optionPropertyPrefix) && 0 < count($value)) {
                $optionQuery = $this->hasOptionsQueryBuilder->buildQuery(['option' => $key, 'option_values' => $value]);
                if (null !== $optionQuery) {
                    $boolQuery->addMust($optionQuery);
                }
            }
        }
    }

    private function resolveAttributeQuery(BoolQuery $boolQuery, array $data): void
    {
        foreach ($data as $key => $value) {
            if (0 === strpos($key, $this->attributePropertyPrefix) && 0 < count($value)) {
                $optionQuery = $this->hasAttributesQueryBuilder->buildQuery(['attribute' => $key, 'attribute_values' => $value]);
                if (null !== $optionQuery) {
                    $boolQuery->addMust($optionQuery);
                }
            }
        }
    }

    private function addMustIfNotNull(?AbstractQuery $query, BoolQuery $boolQuery): void
    {
        if (null !== $query) {
            $boolQuery->addMust($query);
        }
    }
}
