<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductOptionRepository implements ProductOptionRepositoryInterface
{
    public function __construct(
        private RepositoryInterface $productOptionRepository
    ) {
    }

    public function findAllWithTranslations(?string $locale): array
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->productOptionRepository->createQueryBuilder('o');

        if (null !== $locale) {
            $queryBuilder
                ->addSelect('translation')
                ->leftJoin('o.translations', 'translation', 'ot')
                ->andWhere('translation.locale = :locale')
                ->setParameter('locale', $locale)
            ;
        }

        return $queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }
}
