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
use Elastica\Query\Match;

final class NameQueryBuilder implements QueryBuilderInterface
{
    /**
     * @var string
     */
    private $nameProperty;

    /**
     * @param string $nameProperty
     */
    public function __construct(string $nameProperty)
    {
        $this->nameProperty = $nameProperty;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): ?AbstractQuery
    {
        if (!$name = $data[$this->nameProperty]) {
            return null;
        }

        $nameQuery = new Match();
        $nameQuery->setFieldQuery($this->nameProperty, $name);

        return $nameQuery;
    }
}
