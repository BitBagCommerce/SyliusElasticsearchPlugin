<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use Elastica\Query\AbstractQuery;
use Elastica\Query\Term;

final class IsEnabledQueryBuilder implements QueryBuilderInterface
{
    /** @var string */
    private $enabledProperty;

    public function __construct(string $enabledProperty)
    {
        $this->enabledProperty = $enabledProperty;
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $enabledQuery = new Term();
        $enabledQuery->setTerm($this->enabledProperty, true);

        return $enabledQuery;
    }
}
