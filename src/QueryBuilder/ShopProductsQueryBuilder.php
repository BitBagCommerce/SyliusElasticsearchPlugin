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

final class ShopProductsQueryBuilder implements QueryBuilderInterface
{
    /**
     * @var QueryBuilderInterface
     */
    private $isEnabledQueryBuilder;

    /**
     * @var QueryBuilderInterface
     */
    private $hasChannelQueryBuilder;

    /**
     * @var QueryBuilderInterface
     */
    private $containsNameQueryBuilder;

    /**
     * @var QueryBuilderInterface
     */
    private $hasTaxonQueryBuilder;

    /**
     * @var QueryBuilderInterface
     */
    private $hasOptionsQueryBuilder;

    /**
     * @var QueryBuilderInterface
     */
    private $hasAttributesQueryBuilder;

    /**
     * @var QueryBuilderInterface
     */
    private $hasPriceBetweenQueryBuilder;

    /**
     * @var string
     */
    private $optionPropertyPrefix;

    /**
     * @var string
     */
    private $attributePropertyPrefix;

    /**
     * @param QueryBuilderInterface $isEnabledQueryBuilder
     * @param QueryBuilderInterface $hasChannelQueryBuilder
     * @param QueryBuilderInterface $containsNameQueryBuilder
     * @param QueryBuilderInterface $hasTaxonQueryBuilder
     * @param QueryBuilderInterface $hasOptionsQueryBuilder
     * @param QueryBuilderInterface $hasAttributesQueryBuilder
     * @param QueryBuilderInterface $hasPriceBetweenQueryBuilder
     * @param string $optionPropertyPrefix
     * @param string $attributePropertyPrefix
     */
    public function __construct(
        QueryBuilderInterface $isEnabledQueryBuilder,
        QueryBuilderInterface $hasChannelQueryBuilder,
        QueryBuilderInterface $containsNameQueryBuilder,
        QueryBuilderInterface $hasTaxonQueryBuilder,
        QueryBuilderInterface $hasOptionsQueryBuilder,
        QueryBuilderInterface $hasAttributesQueryBuilder,
        QueryBuilderInterface $hasPriceBetweenQueryBuilder,
        string $optionPropertyPrefix,
        string $attributePropertyPrefix
    ) {
        $this->isEnabledQueryBuilder = $isEnabledQueryBuilder;
        $this->hasChannelQueryBuilder = $hasChannelQueryBuilder;
        $this->containsNameQueryBuilder = $containsNameQueryBuilder;
        $this->hasTaxonQueryBuilder = $hasTaxonQueryBuilder;
        $this->hasOptionsQueryBuilder = $hasOptionsQueryBuilder;
        $this->hasAttributesQueryBuilder = $hasAttributesQueryBuilder;
        $this->hasPriceBetweenQueryBuilder = $hasPriceBetweenQueryBuilder;
        $this->optionPropertyPrefix = $optionPropertyPrefix;
        $this->attributePropertyPrefix = $attributePropertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $boolQuery->addMust($this->isEnabledQueryBuilder->buildQuery($data));
        $boolQuery->addMust($this->hasChannelQueryBuilder->buildQuery($data));

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

    /**
     * @param BoolQuery $boolQuery
     * @param array $data
     */
    private function resolveOptionQuery(BoolQuery $boolQuery, array $data): void
    {
        foreach ($data as $key => $value) {
            if (0 === strpos($key, $this->optionPropertyPrefix) && 0 < count($value)) {
                $optionQuery = $this->hasOptionsQueryBuilder->buildQuery(['option' => $key, 'option_values' => $value]);
                $boolQuery->addMust($optionQuery);
            }
        }
    }

    /**
     * @param BoolQuery $boolQuery
     * @param array $data
     */
    private function resolveAttributeQuery(BoolQuery $boolQuery, array $data): void
    {
        foreach ($data as $key => $value) {
            if (0 === strpos($key, $this->attributePropertyPrefix) && 0 < count($value)) {
                $optionQuery = $this->hasAttributesQueryBuilder->buildQuery(['attribute' => $key, 'attribute_values' => $value]);
                $boolQuery->addMust($optionQuery);
            }
        }
    }

    /**
     * @param AbstractQuery|null $query
     * @param BoolQuery $boolQuery
     */
    private function addMustIfNotNull(?AbstractQuery $query, BoolQuery $boolQuery): void
    {
        if (null !== $query) {
            $boolQuery->addMust($query);
        }
    }
}
