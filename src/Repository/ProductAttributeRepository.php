<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductAttributeRepository implements ProductAttributeRepositoryInterface
{
    /** @var RepositoryInterface */
    private $productAttributeRepository;

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
