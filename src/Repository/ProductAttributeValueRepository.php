<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Product\Repository\ProductAttributeValueRepositoryInterface as BaseAttributeValueRepositoryInterface;
use Sylius\Component\Taxonomy\Model\Taxon;

class ProductAttributeValueRepository implements ProductAttributeValueRepositoryInterface
{
    public function __construct(
        private BaseAttributeValueRepositoryInterface $baseAttributeValueRepository,
        private bool $includeAllDescendants
    ) {
    }

    public function getUniqueAttributeValues(AttributeInterface $productAttribute, Taxon $taxon): array
    {
        $queryBuilder = $this->baseAttributeValueRepository->createQueryBuilder('o');

        /** @var string|null $storageType */
        $storageType = $productAttribute->getStorageType();

        $queryBuilder
            ->join('o.subject', 'p', 'WITH', 'p.enabled = 1')
            ->join('p.productTaxons', 't', 'WITH', 't.product = p.id')
            ->select('o.localeCode, o.' . $storageType . ' as value')
            ->andWhere('t.taxon = :taxon');

        if (true === $this->includeAllDescendants) {
            $queryBuilder->innerJoin('t.taxon', 'taxon')
                ->orWhere('taxon.left >= :taxonLeft and taxon.right <= :taxonRight and taxon.root = :taxonRoot')
                ->setParameter('taxonLeft', $taxon->getLeft())
                ->setParameter('taxonRight', $taxon->getRight())
                ->setParameter('taxonRoot', $taxon->getRoot());
        }

        return $queryBuilder
            ->andWhere('o.attribute = :attribute')
            ->groupBy('o.' . $storageType)
            ->addGroupBy('o.localeCode')
            ->setParameter('attribute', $productAttribute)
            ->setParameter('taxon', $taxon->getId())
            ->getQuery()
            ->getResult()
            ;
    }
}
