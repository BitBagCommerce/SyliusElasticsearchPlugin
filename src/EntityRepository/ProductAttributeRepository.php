<?php

declare(strict_types = 1);

namespace BitBag\SyliusElasticsearchPlugin\EntityRepository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ProductBundle\Doctrine\ORM\ProductAttributeValueRepository as BaseRepository;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;

class ProductAttributeRepository implements ProductAttributeValueRepositoryInterface
{

    /**
     * @var BaseRepository
     */
    protected $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AttributeInterface $productAttribute
     *
     * @return array|ProductAttributeValueInterface[]
     */
    public function getUniqueAttributeValues(AttributeInterface $productAttribute): array
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->repository->createQueryBuilder('o');

        return $queryBuilder
            ->where('o.attribute = :attribute')
            ->groupBy('o.'.$productAttribute->getStorageType())
            ->setParameter(':attribute', $productAttribute)
            ->getQuery()
            ->getResult();
    }

}
