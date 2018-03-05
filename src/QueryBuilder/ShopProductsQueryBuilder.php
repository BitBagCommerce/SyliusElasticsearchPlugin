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
     * @var string
     */
    private $optionPropertyPrefix;

    /**
     * @param QueryBuilderInterface $isEnabledQueryBuilder
     * @param QueryBuilderInterface $hasChannelQueryBuilder
     * @param QueryBuilderInterface $containsNameQueryBuilder
     * @param QueryBuilderInterface $hasTaxonQueryBuilder
     * @param QueryBuilderInterface $hasOptionsQueryBuilder
     * @param string $optionPropertyPrefix
     */
    public function __construct(
        QueryBuilderInterface $isEnabledQueryBuilder,
        QueryBuilderInterface $hasChannelQueryBuilder,
        QueryBuilderInterface $containsNameQueryBuilder,
        QueryBuilderInterface $hasTaxonQueryBuilder,
        QueryBuilderInterface $hasOptionsQueryBuilder,
        string $optionPropertyPrefix
    )
    {
        $this->isEnabledQueryBuilder = $isEnabledQueryBuilder;
        $this->hasChannelQueryBuilder = $hasChannelQueryBuilder;
        $this->containsNameQueryBuilder = $containsNameQueryBuilder;
        $this->hasTaxonQueryBuilder = $hasTaxonQueryBuilder;
        $this->hasOptionsQueryBuilder = $hasOptionsQueryBuilder;
        $this->optionPropertyPrefix = $optionPropertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $boolQuery->addMust($this->isEnabledQueryBuilder->buildQuery($data));
        $boolQuery->addMust($this->hasChannelQueryBuilder->buildQuery($data));

        if ($nameQuery = $this->containsNameQueryBuilder->buildQuery($data)) {
            $boolQuery->addMust($nameQuery);
        }

        if ($taxonQuery = $this->hasTaxonQueryBuilder->buildQuery($data)) {
            $boolQuery->addMust($taxonQuery);
        }

        $this->buildOptionQuery($boolQuery, $data);

        return $boolQuery;
    }

    /**
     * @param BoolQuery $boolQuery
     * @param array $data
     */
    private function buildOptionQuery(BoolQuery $boolQuery, array $data): void
    {
        foreach ($data as $key => $value) {
            if (0 === strpos($key, $this->optionPropertyPrefix)) {
                $optionQuery = $this->hasOptionsQueryBuilder->buildQuery(['option_index' => $key, 'options' => $value]);

                $boolQuery->addMust($optionQuery);
            }
        }
    }
}
