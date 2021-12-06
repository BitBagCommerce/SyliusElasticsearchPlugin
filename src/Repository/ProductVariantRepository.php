<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

use Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface as BaseProductVariantRepositoryInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

class ProductVariantRepository implements ProductVariantRepositoryInterface
{
    /** @var BaseProductVariantRepositoryInterface|EntityRepository */
    private $baseProductVariantRepository;

    public function __construct(BaseProductVariantRepositoryInterface $baseProductVariantRepository)
    {
        $this->baseProductVariantRepository = $baseProductVariantRepository;
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
}
