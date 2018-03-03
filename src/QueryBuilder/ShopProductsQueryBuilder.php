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
    private $enabledQueryBuilder;

    /**
     * @var QueryBuilderInterface
     */
    private $channelQueryBuilder;

    /**
     * @var QueryBuilderInterface
     */
    private $nameQueryBuilder;

    /**
     * @var QueryBuilderInterface
     */
    private $taxonQueryBuilder;

    /**
     * @var QueryBuilderInterface
     */
    private $optionQueryBuilder;

    /**
     * @var string
     */
    private $optionPropertyPrefix;

    /**
     * @param QueryBuilderInterface $enabledQueryBuilder
     * @param QueryBuilderInterface $channelQueryBuilder
     * @param QueryBuilderInterface $nameQueryBuilder
     * @param QueryBuilderInterface $taxonQueryBuilder
     * @param QueryBuilderInterface $optionQueryBuilder
     * @param string $optionPropertyPrefix
     */
    public function __construct(
        QueryBuilderInterface $enabledQueryBuilder,
        QueryBuilderInterface $channelQueryBuilder,
        QueryBuilderInterface $nameQueryBuilder,
        QueryBuilderInterface $taxonQueryBuilder,
        QueryBuilderInterface $optionQueryBuilder,
        string $optionPropertyPrefix
    )
    {
        $this->enabledQueryBuilder = $enabledQueryBuilder;
        $this->channelQueryBuilder = $channelQueryBuilder;
        $this->nameQueryBuilder = $nameQueryBuilder;
        $this->taxonQueryBuilder = $taxonQueryBuilder;
        $this->optionQueryBuilder = $optionQueryBuilder;
        $this->optionPropertyPrefix = $optionPropertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $boolQuery->addMust($this->enabledQueryBuilder->buildQuery($data));
        $boolQuery->addMust($this->channelQueryBuilder->buildQuery($data));

        if ($nameQuery = $this->nameQueryBuilder->buildQuery($data)) {
            $boolQuery->addMust($nameQuery);
        }

        if ($taxonQuery = $this->taxonQueryBuilder->buildQuery($data)) {
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
                $optionQuery = $this->optionQueryBuilder->buildQuery(['option_index' => $key, 'options' => $value]);

                $boolQuery->addMust($optionQuery);
            }
        }
    }
}
