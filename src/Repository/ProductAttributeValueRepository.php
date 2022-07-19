<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Product\Repository\ProductAttributeValueRepositoryInterface as BaseAttributeValueRepositoryInterface;
use Sylius\Component\Taxonomy\Model\Taxon;

class ProductAttributeValueRepository implements ProductAttributeValueRepositoryInterface
{
    /** @var BaseAttributeValueRepositoryInterface */
    private $baseAttributeValueRepository;

    public function __construct(BaseAttributeValueRepositoryInterface $baseAttributeValueRepository)
    {
        $this->baseAttributeValueRepository = $baseAttributeValueRepository;
    }

    public function getUniqueAttributeValues(AttributeInterface $productAttribute, Taxon $taxon): array
    {
        $queryBuilder = $this->baseAttributeValueRepository->createQueryBuilder('o');

        /** @var string|null $storageType */
        $storageType = $productAttribute->getStorageType();

        return $queryBuilder
            ->join('o.subject', 'p', 'WITH', 'p.enabled = 1')
            ->join('p.productTaxons', 't', 'WITH', 't.product = p.id')
            ->select('o.localeCode, o.' . $storageType . ' as value')
            ->where('o.attribute = :attribute')
            ->andWhere('t.taxon = :taxon')
            ->groupBy('o.' . $storageType)
            ->addGroupBy('o.localeCode')
            ->setParameters([
                ':attribute' => $productAttribute,
                ':taxon' => $taxon->getId(),
            ])
            ->getQuery()
            ->getResult()
            ;
    }
}
