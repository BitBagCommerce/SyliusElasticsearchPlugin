<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query\AbstractQuery;
use FOS\ElasticaBundle\Finder\FinderInterface;
use Sylius\Component\Core\Model\TaxonInterface;

final class ProductAttributesFinder implements ProductAttributesFinderInterface
{
    public function __construct(
        private FinderInterface $attributesFinder,
        private QueryBuilderInterface $attributesByTaxonQueryBuilder,
        private string $taxonsProperty,
        private int $filterMax = 20,
        ) {
    }

    public function findByTaxon(TaxonInterface $taxon): ?array
    {
        $data = [];
        $data[$this->taxonsProperty] = strtolower((string) $taxon->getCode());

        /** @var AbstractQuery $query */
        $query = $this->attributesByTaxonQueryBuilder->buildQuery($data);

        return $this->attributesFinder->find($query, $this->filterMax);
    }
}
