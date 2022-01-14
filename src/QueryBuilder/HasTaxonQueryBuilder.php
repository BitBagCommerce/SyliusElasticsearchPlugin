<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use Elastica\Query\AbstractQuery;
use Elastica\Query\Terms;

final class HasTaxonQueryBuilder implements QueryBuilderInterface
{
    /** @var string */
    private $taxonsProperty;

    public function __construct(string $taxonsProperty)
    {
        $this->taxonsProperty = $taxonsProperty;
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        if (!$taxonCode = $data[$this->taxonsProperty]) {
            return null;
        }

        $taxonQuery = new Terms($this->taxonsProperty);
        $taxonQuery->setTerms([$taxonCode]);

        return $taxonQuery;
    }
}
