<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Product\Repository\ProductAttributeValueRepositoryInterface as BaseAttributeValueRepositoryInterface;

final class ProductAttributeValueRepository implements ProductAttributeValueRepositoryInterface
{
    /** @var BaseAttributeValueRepositoryInterface */
    private $baseAttributeValueRepository;

    public function __construct(BaseAttributeValueRepositoryInterface $baseAttributeValueRepository)
    {
        $this->baseAttributeValueRepository = $baseAttributeValueRepository;
    }

    public function getUniqueAttributeValues(AttributeInterface $productAttribute): array
    {
        $queryBuilder = $this->baseAttributeValueRepository->createQueryBuilder('o');

        /** @var null|string $storageType */
        $storageType = $productAttribute->getStorageType();

        return $queryBuilder
            ->join('o.subject', 'p', 'WITH', 'p.enabled = 1')
            ->select('o.localeCode, o.'.$storageType.' as value')
            ->where('o.attribute = :attribute')
            ->groupBy('o.' . $storageType)
            ->addGroupBy('o.localeCode')
            ->setParameter(':attribute', $productAttribute)
            ->getQuery()
            ->getResult()
        ;
    }
}
