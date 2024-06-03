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
use Doctrine\ORM\Query\Expr\Join;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface as BaseTaxonRepositoryInterface;

class TaxonRepository implements TaxonRepositoryInterface
{
    public function __construct(
        private BaseTaxonRepositoryInterface|EntityRepository $baseTaxonRepository,
        private ProductRepositoryInterface|EntityRepository $productRepository,
        private string $productTaxonEntityClass,
        private string $productAttributeEntityClass
    ) {
    }

    public function getTaxonsByAttributeViaProduct(AttributeInterface $attribute): array
    {
        return $this->baseTaxonRepository
            ->createQueryBuilder('t')
            ->distinct(true)
            ->select('t')
            ->leftJoin($this->productTaxonEntityClass, 'pt', Join::WITH, 'pt.taxon = t.id')
            ->where(
                'pt.product IN(' .
                $this
                    ->productRepository->createQueryBuilder('p')
                    ->leftJoin($this->productAttributeEntityClass, 'pav', Join::WITH, 'pav.subject = p.id')
                    ->where('pav.attribute = :attribute')
                    ->getQuery()
                    ->getDQL()
                . ')'
            )
            ->setParameter(':attribute', $attribute)
            ->getQuery()
            ->getResult()
        ;
    }
}
