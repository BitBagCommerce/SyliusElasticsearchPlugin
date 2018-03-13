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
use Elastica\Query\Term;

final class IsEnabledQueryBuilder implements QueryBuilderInterface
{
    /**
     * @var string
     */
    private $enabledProperty;

    /**
     * @param string $enabledProperty
     */
    public function __construct(string $enabledProperty)
    {
        $this->enabledProperty = $enabledProperty;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): ?AbstractQuery
    {
        $enabledQuery = new Term();
        $enabledQuery->setTerm($this->enabledProperty, true);

        return $enabledQuery;
    }
}
