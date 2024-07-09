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
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductOptionRepository implements ProductOptionRepositoryInterface
{
    public function __construct(
        private RepositoryInterface|EntityRepository $productOptionRepository
    ) {
    }

    public function findAllWithTranslations(?string $locale): array
    {
        /** @var EntityRepository $productOptionRepository */
        $productOptionRepository = $this->productOptionRepository;

        $queryBuilder = $productOptionRepository->createQueryBuilder('o');

        if (null !== $locale) {
            $queryBuilder
                ->addSelect('translation')
                ->leftJoin('o.translations', 'ot')
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
