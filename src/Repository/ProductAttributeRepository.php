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

use Doctrine\DBAL\Query\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductAttributeRepository implements ProductAttributeRepositoryInterface
{
    private RepositoryInterface $productAttributeRepository;

    public function __construct(RepositoryInterface $productAttributeRepository)
    {
        $this->productAttributeRepository = $productAttributeRepository;
    }

    public function getAttributeTypeByName(string $attributeName): string
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->productAttributeRepository->createQueryBuilder('p');

        $result = $queryBuilder
            ->select('p.type')
            ->where('p.code = :code')
            ->setParameter(':code', $attributeName)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['type'];
    }
}
