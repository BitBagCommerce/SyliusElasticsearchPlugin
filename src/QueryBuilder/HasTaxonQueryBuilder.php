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
use Elastica\Query\Terms;

final class HasTaxonQueryBuilder implements QueryBuilderInterface
{
    public function __construct(
        private string $taxonsProperty
    ) {
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
