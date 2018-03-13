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
use Elastica\Query\Terms;

final class HasTaxonQueryBuilder implements QueryBuilderInterface
{
    /**
     * @var string
     */
    private $taxonsProperty;

    /**
     * @param string $taxonsProperty
     */
    public function __construct(string $taxonsProperty)
    {
        $this->taxonsProperty = $taxonsProperty;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(array $data): ?AbstractQuery
    {
        if (!$taxonCode = $data[$this->taxonsProperty]) {
            return null;
        }

        $taxonQuery = new Terms();
        $taxonQuery->setTerms($this->taxonsProperty, [$taxonCode]);

        return $taxonQuery;
    }
}
