<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use FOS\ElasticaBundle\Finder\FinderInterface;
use Sylius\Component\Core\Model\TaxonInterface;

final class ProductOptionsFinder implements ProductOptionsFinderInterface
{
    /** @var FinderInterface */
    private $optionsFinder;

    /** @var QueryBuilderInterface */
    private $productOptionsByTaxonQueryBuilder;

    /** @var string */
    private $taxonsProperty;

    public function __construct(
        FinderInterface $optionsFinder,
        QueryBuilderInterface $productOptionsByTaxonQueryBuilder,
        string $taxonsProperty
    ) {
        $this->optionsFinder = $optionsFinder;
        $this->productOptionsByTaxonQueryBuilder = $productOptionsByTaxonQueryBuilder;
        $this->taxonsProperty = $taxonsProperty;
    }

    public function findByTaxon(TaxonInterface $taxon): ?array
    {
        $data = [];
        $data[$this->taxonsProperty] = strtolower($taxon->getCode());

        $query = $this->productOptionsByTaxonQueryBuilder->buildQuery($data);

        return $this->optionsFinder->find($query, 20);
    }
}
