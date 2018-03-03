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
use Elastica\Query\Match;
use Elastica\Query\Term;
use Elastica\Query\Terms;

final class ShopProductsQueryBuilder implements QueryBuilderInterface
{
    /**
     * @var string
     */
    private $nameProperty;

    /**
     * @var string
     */
    private $enabledProperty;

    /**
     * @var string
     */
    private $taxonsProperty;

    /**
     * @var string
     */
    private $optionPropertyPrefix;

    /**
     * @var string
     */
    private $attributePropertyPrefix;

    /**
     * @param string $nameProperty
     * @param string $enabledProperty
     * @param string $taxonsProperty
     * @param string $optionPropertyPrefix
     * @param string $attributePropertyPrefix
     */
    public function __construct(
        string $nameProperty,
        string $enabledProperty,
        string $taxonsProperty,
        string $optionPropertyPrefix,
        string $attributePropertyPrefix
    )
    {
        $this->nameProperty = $nameProperty;
        $this->enabledProperty = $enabledProperty;
        $this->taxonsProperty = $taxonsProperty;
        $this->optionPropertyPrefix = $optionPropertyPrefix;
        $this->attributePropertyPrefix = $attributePropertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $enabledQuery = new Term();
        $enabledQuery->setTerm($this->enabledProperty, true);
        $boolQuery->addMust($enabledQuery);

        if ($name = $data[$this->nameProperty]) {
            $nameQuery = new Match();
            $nameQuery->setFieldQuery($this->nameProperty, $name);
            $boolQuery->addMust($nameQuery);
        }

        if ($taxons = $data[$this->taxonsProperty]) {
            $taxonQuery = new Terms();
            $taxonQuery->setTerms($this->taxonsProperty, (array) $taxons);
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
            if (0 === strpos($this->optionPropertyPrefix, $key)) {
                $optionQuery = new Terms();
                $optionQuery->setTerms($key, (array)$value);
                $boolQuery->addMust($optionQuery);
            }
        }
    }
}
