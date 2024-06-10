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

use Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface as BaseProductVariantRepositoryInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

class ProductVariantRepository implements ProductVariantRepositoryInterface
{
    public function __construct(
        private BaseProductVariantRepositoryInterface|EntityRepository $baseProductVariantRepository
    ) {
    }

    public function findOneByOptionValue(ProductOptionValueInterface $productOptionValue): ?ProductVariantInterface
    {
        return $this->baseProductVariantRepository->createQueryBuilder('o')
            ->where(':optionValue MEMBER OF o.optionValues')
            ->setParameter('optionValue', $productOptionValue)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }

    public function findByOptionValue(ProductOptionValueInterface $productOptionValue): array
    {
        return $this->baseProductVariantRepository->createQueryBuilder('o')
            ->where(':optionValue MEMBER OF o.optionValues')
            ->setParameter('optionValue', $productOptionValue)
            ->getQuery()
            ->getResult()
        ;
    }
}
